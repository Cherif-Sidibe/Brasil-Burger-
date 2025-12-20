using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Authorization;
using Microsoft.EntityFrameworkCore;
using System.Security.Claims;
using VueClient.Data;
using VueClient.Models;
using VueClient.Services;

namespace VueClient.Controllers;

public class CommandeController : Controller
{
    private readonly ICommandeService _commandeService;
    private readonly IPanierService _panierService;
    private readonly AppDbContext _context;

    public CommandeController(ICommandeService commandeService, IPanierService panierService, AppDbContext context)
    {
        _commandeService = commandeService;
        _panierService = panierService;
        _context = context;
    }

    [HttpGet]
    [Authorize]
    public async Task<IActionResult> ConfirmerCommande(string typeLivraison, int? idZone)
    {
        var panier = _panierService.ObtenirPanier();
        if (!panier.Any())
        {
            TempData["ErrorMessage"] = "Votre panier est vide";
            TempData["NotificationType"] = "error";
            return RedirectToAction("Index", "Panier");
        }

        TypeLivraisonEnum typeLivraisonEnum;
        if (Enum.TryParse<TypeLivraisonEnum>(typeLivraison, out var parsedEnum))
        {
            typeLivraisonEnum = parsedEnum;
        }
        else
        {
            typeLivraisonEnum = typeLivraison switch
            {
                "dine-in" => TypeLivraisonEnum.SUR_PLACE,
                "takeaway" => TypeLivraisonEnum.A_RECUPERER,
                "delivery" => TypeLivraisonEnum.A_LIVRER,
                _ => TypeLivraisonEnum.SUR_PLACE
            };
        }

        if (typeLivraisonEnum == TypeLivraisonEnum.A_LIVRER && !idZone.HasValue)
        {
            TempData["ErrorMessage"] = "Veuillez sélectionner une zone de livraison";
            TempData["NotificationType"] = "error";
            return RedirectToAction("Index", "Panier", new { typeLivraison });
        }

        decimal fraisLivraison = 0;
        if (idZone.HasValue)
        {
            var zone = await _context.Zones.FindAsync(idZone.Value);
            if (zone != null)
            {
                fraisLivraison = zone.PrixLivraison;
            }
        }

        var sousTotal = _panierService.ObtenirTotal();
        var total = sousTotal + fraisLivraison;

        ViewBag.TypeLivraison = typeLivraisonEnum;
        ViewBag.IdZone = idZone;
        ViewBag.SousTotal = sousTotal;
        ViewBag.FraisLivraison = fraisLivraison;
        ViewBag.Total = total;
        ViewBag.Panier = panier;

        return View();
    }

    [HttpPost]
    [Authorize]
    public async Task<IActionResult> CreerCommande(string typeLivraison, int? idZone, string methodePaiement, string? adresseLivraison)
    {
        try
        {
            var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier);
            if (userIdClaim == null || !int.TryParse(userIdClaim.Value, out int idClient))
            {
                TempData["ErrorMessage"] = "Vous devez être connecté pour passer une commande.";
                return RedirectToAction("Login", "Account", new { returnUrl = Url.Action("ConfirmerCommande", "Commande") });
            }

            TypeLivraisonEnum typeLivraisonEnum = Enum.Parse<TypeLivraisonEnum>(typeLivraison);
            MethodePaiementEnum methodePaiementEnum = Enum.Parse<MethodePaiementEnum>(methodePaiement);

            var idCommande = await _commandeService.CreerCommandeAsync(
                idClient,
                typeLivraisonEnum,
                idZone,
                adresseLivraison,
                methodePaiementEnum
            );

            TempData["SuccessMessage"] = "Votre commande a été créée avec succès !";
            TempData["NotificationType"] = "success";
            TempData["CommandeId"] = idCommande;
            return RedirectToAction("Details", new { id = idCommande });
        }
        catch (Exception ex)
        {
            TempData["ErrorMessage"] = ex.Message;
            TempData["NotificationType"] = "error";
            return RedirectToAction("Index", "Panier");
        }
    }

    [Authorize]
    public async Task<IActionResult> Details(int id)
    {
        var commande = await _commandeService.ObtenirCommandeAsync(id);

        if (commande == null)
        {
            TempData["ErrorMessage"] = "Commande non trouvée";
            TempData["NotificationType"] = "error";
            return RedirectToAction("Index", "Catalogue");
        }

        var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier);
        if (userIdClaim == null || !int.TryParse(userIdClaim.Value, out int idClient) || commande.IdClient != idClient)
        {
            TempData["ErrorMessage"] = "Vous n'avez pas accès à cette commande.";
            TempData["NotificationType"] = "error";
            return RedirectToAction("MesCommandes");
        }

        return View(commande);
    }

    [HttpGet]
    public async Task<IActionResult> GetCommandeStatus(int id)
    {
        var commande = await _context.Commandes
            .Include(c => c.Paiement)
            .FirstOrDefaultAsync(c => c.Id == id);

        if (commande == null)
        {
            return NotFound();
        }

        return Json(new
        {
            id = commande.Id,
            etatCommande = commande.EtatCommande.ToString(),
            statutPaiement = commande.Paiement?.StatutPaiement.ToString(),
            dateCommande = commande.DateCommande,
            montantTotal = commande.MontantTotal
        });
    }

    [Authorize]
    public async Task<IActionResult> MesCommandes()
    {
        var userIdClaim = User.FindFirst(ClaimTypes.NameIdentifier);
        if (userIdClaim == null || !int.TryParse(userIdClaim.Value, out int idClient))
        {
            TempData["ErrorMessage"] = "Vous devez être connecté pour voir vos commandes.";
            return RedirectToAction("Login", "Account", new { returnUrl = Url.Action("MesCommandes", "Commande") });
        }

        var commandes = await _commandeService.ObtenirCommandesClientAsync(idClient);

        return View(commandes);
    }

    public IActionResult Index()
    {
        return RedirectToAction("MesCommandes");
    }
}
