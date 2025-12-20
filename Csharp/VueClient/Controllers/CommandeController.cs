using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
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

    /// <summary>
    /// Affiche la page de sélection du mode de paiement
    /// </summary>
    [HttpPost]
    public async Task<IActionResult> ConfirmerCommande(string typeLivraison, int? idZone)
    {
        // Vérifier que le panier n'est pas vide
        var panier = _panierService.ObtenirPanier();
        if (!panier.Any())
        {
            TempData["ErrorMessage"] = "Votre panier est vide";
            return RedirectToAction("Index", "Panier");
        }

        // Convertir le type de livraison
        TypeLivraisonEnum typeLivraisonEnum = typeLivraison switch
        {
            "dine-in" => TypeLivraisonEnum.SUR_PLACE,
            "takeaway" => TypeLivraisonEnum.A_RECUPERER,
            "delivery" => TypeLivraisonEnum.A_LIVRER,
            _ => TypeLivraisonEnum.SUR_PLACE
        };

        // Valider la zone si livraison
        if (typeLivraisonEnum == TypeLivraisonEnum.A_LIVRER && !idZone.HasValue)
        {
            TempData["ErrorMessage"] = "Veuillez sélectionner une zone de livraison";
            return RedirectToAction("Index", "Panier", new { typeLivraison });
        }

        // Calculer les frais de livraison si nécessaire
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

        // Passer les infos à la vue de paiement
        ViewBag.TypeLivraison = typeLivraisonEnum;
        ViewBag.IdZone = idZone;
        ViewBag.SousTotal = sousTotal;
        ViewBag.FraisLivraison = fraisLivraison;
        ViewBag.Total = total;
        ViewBag.Panier = panier;

        return View();
    }

    /// <summary>
    /// Traite la création de la commande
    /// </summary>
    [HttpPost]
    public async Task<IActionResult> CreerCommande(string typeLivraison, int? idZone, string methodePaiement, string? adresseLivraison)
    {
        try
        {
            // Convertir les strings en enums
            TypeLivraisonEnum typeLivraisonEnum = Enum.Parse<TypeLivraisonEnum>(typeLivraison);
            MethodePaiementEnum methodePaiementEnum = Enum.Parse<MethodePaiementEnum>(methodePaiement);

            // Créer la commande
            var idCommande = await _commandeService.CreerCommandeAsync(
                typeLivraisonEnum,
                idZone,
                adresseLivraison,
                methodePaiementEnum
            );

            TempData["SuccessMessage"] = "Votre commande a été créée avec succès !";
            return RedirectToAction("Details", new { id = idCommande });
        }
        catch (Exception ex)
        {
            TempData["ErrorMessage"] = ex.Message;
            return RedirectToAction("Index", "Panier");
        }
    }

    /// <summary>
    /// Affiche les détails d'une commande
    /// </summary>
    public async Task<IActionResult> Details(int id)
    {
        var commande = await _commandeService.ObtenirCommandeAsync(id);

        if (commande == null)
        {
            TempData["ErrorMessage"] = "Commande non trouvée";
            return RedirectToAction("Index", "Catalogue");
        }

        return View(commande);
    }

    /// <summary>
    /// Affiche l'historique des commandes du client
    /// </summary>
    public async Task<IActionResult> MesCommandes()
    {
        // Utiliser l'ID client test (1) pour le moment
        const int idClientTest = 1;

        var commandes = await _commandeService.ObtenirCommandesClientAsync(idClientTest);

        return View(commandes);
    }

    /// <summary>
    /// Page principale des commandes (redirection vers MesCommandes)
    /// </summary>
    public IActionResult Index()
    {
        return RedirectToAction("MesCommandes");
    }
}
