using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Models;

namespace VueClient.Services.Impl;

public class CatalogueService : ICatalogueService
{
    private readonly AppDbContext _context;

    public CatalogueService(AppDbContext context)
    {
        _context = context;
    }

    public async Task<List<Burger>> GetBurgersActifs()
    {
        return await _context.Burgers
            .Where(b => !b.IsArchive)
            .OrderBy(b => b.Nom)
            .ToListAsync();
    }

    public async Task<List<Menu>> GetMenusActifs()
    {
        return await _context.Menus
            .Include(m => m.Burger)
            .Include(m => m.ComplementBoisson)
            .Include(m => m.ComplementFrite)
            .Where(m => !m.IsArchive && !m.Burger.IsArchive && !m.ComplementBoisson.IsArchive && !m.ComplementFrite.IsArchive)
            .OrderBy(m => m.Nom)
            .ToListAsync();
    }

    public async Task<List<Complement>> GetComplementsActifs()
    {
        return await _context.Complements
            .Where(c => !c.IsArchive)
            .OrderBy(c => c.TypeComplement)
            .ThenBy(c => c.Nom)
            .ToListAsync();
    }

    public async Task<Burger?> GetBurgerDetails(int id)
    {
        return await _context.Burgers
            .Where(b => b.Id == id && !b.IsArchive)
            .FirstOrDefaultAsync();
    }

    public async Task<Menu?> GetMenuDetails(int id)
    {
        return await _context.Menus
            .Include(m => m.Burger)
            .Include(m => m.ComplementBoisson)
            .Include(m => m.ComplementFrite)
            .Where(m => m.Id == id && !m.IsArchive)
            .FirstOrDefaultAsync();
    }

    public decimal CalculerPrixMenu(int idBurger, int idBoisson, int idFrite)
    {
        var burger = _context.Burgers.Find(idBurger);
        var boisson = _context.Complements.Find(idBoisson);
        var frite = _context.Complements.Find(idFrite);

        if (burger == null || boisson == null || frite == null)
            return 0;

        return burger.Prix + boisson.Prix + frite.Prix;
    }
}
