using Microsoft.AspNetCore.Mvc;
using VueClient.Data;
using VueClient.Services;
using VueClient.ViewModels;
using Microsoft.EntityFrameworkCore;

namespace VueClient.Controllers;

public class PanierController : Controller
{
    private readonly IPanierService _panierService;
    private readonly AppDbContext _context;

    public PanierController(IPanierService panierService, AppDbContext context)
    {
        _panierService = panierService;
        _context = context;
    }

    public async Task<IActionResult> Index(int? idZone, string? typeLivraison)
    {
        var panier = _panierService.ObtenirPanier();
        var sousTotal = _panierService.ObtenirTotal();

        var zones = await _context.Zones.Where(z => !z.IsArchive).ToListAsync();
        ViewBag.Zones = zones;

        ViewBag.TypeLivraison = typeLivraison ?? "dine-in";

        decimal fraisLivraison = 0;
        if (idZone.HasValue)
        {
            var zone = zones.FirstOrDefault(z => z.Id == idZone.Value);
            if (zone != null)
            {
                fraisLivraison = zone.PrixLivraison;
                ViewBag.IdZoneSelectionnee = idZone.Value;
            }
        }

        ViewBag.SousTotal = sousTotal;
        ViewBag.FraisLivraison = fraisLivraison;
        ViewBag.Total = sousTotal + fraisLivraison;

        return View(panier);
    }

    [HttpPost]
    public async Task<IActionResult> Ajouter(int id, string type)
    {
        await _panierService.AjouterArticle(id, type);

        TempData["SuccessMessage"] = "Article ajouté au panier avec succès !";

        var referer = Request.Headers["Referer"].ToString();
        if (!string.IsNullOrEmpty(referer))
        {
            return Redirect(referer);
        }

        return RedirectToAction("Index", "Catalogue");
    }

    [HttpPost]
    public IActionResult ModifierQuantite(int id, string type, int quantite)
    {
        _panierService.ModifierQuantite(id, type, quantite);
        return RedirectToAction("Index");
    }

    [HttpPost]
    public IActionResult Retirer(int id, string type)
    {
        _panierService.RetirerArticle(id, type);
        return RedirectToAction("Index");
    }

    [HttpPost]
    public IActionResult Vider()
    {
        _panierService.ViderPanier();
        return RedirectToAction("Index");
    }

    public IActionResult ObtenirNombreArticles()
    {
        var nombre = _panierService.ObtenirNombreArticles();
        return Json(new { nombre });
    }
}
