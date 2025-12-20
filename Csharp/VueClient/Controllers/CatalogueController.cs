using Microsoft.AspNetCore.Mvc;
using VueClient.Models;
using VueClient.Services;
using VueClient.ViewModels;

namespace VueClient.Controllers;

public class CatalogueController : Controller
{
    private readonly ICatalogueService _catalogueService;

    public CatalogueController(ICatalogueService catalogueService)
    {
        _catalogueService = catalogueService;
    }

    public async Task<IActionResult> Index(string filtre = "all")
    {
        var burgers = await _catalogueService.GetBurgersActifs();
        var menus = await _catalogueService.GetMenusActifs();
        var complements = await _catalogueService.GetComplementsActifs();

        var boissons = complements.Where(c => c.TypeComplement == TypeComplementEnum.BOISSON).ToList();
        var frites = complements.Where(c => c.TypeComplement == TypeComplementEnum.FRITES).ToList();

        switch (filtre?.ToLower())
        {
            case "menu":
                burgers = new List<Burger>();
                boissons = new List<Complement>();
                frites = new List<Complement>();
                break;
            case "burger":
                menus = new List<Menu>();
                boissons = new List<Complement>();
                frites = new List<Complement>();
                break;
            case "complement":
                burgers = new List<Burger>();
                menus = new List<Menu>();
                break;
            case "all":
            default:
                break;
        }

        var viewModel = new CatalogueViewModel
        {
            Burgers = burgers,
            Menus = menus,
            Boissons = boissons,
            Frites = frites,
            FiltreActif = filtre ?? "all"
        };

        return View(viewModel);
    }

    public async Task<IActionResult> BurgerDetails(int id)
    {
        var burger = await _catalogueService.GetBurgerDetails(id);
        if (burger == null)
            return NotFound();

        return View(burger);
    }

    public async Task<IActionResult> MenuDetails(int id)
    {
        var menu = await _catalogueService.GetMenuDetails(id);
        if (menu == null)
            return NotFound();

        return View(menu);
    }
}
