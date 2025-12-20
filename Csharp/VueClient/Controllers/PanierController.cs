using Microsoft.AspNetCore.Mvc;
using VueClient.Services;
using VueClient.ViewModels;

namespace VueClient.Controllers;

public class PanierController : Controller
{
    private readonly IPanierService _panierService;

    public PanierController(IPanierService panierService)
    {
        _panierService = panierService;
    }

    public IActionResult Index()
    {
        var panier = _panierService.ObtenirPanier();
        var sousTotal = _panierService.ObtenirTotal();

        ViewBag.SousTotal = sousTotal;
        ViewBag.FraisLivraison = 0; 
        ViewBag.Total = sousTotal;

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
