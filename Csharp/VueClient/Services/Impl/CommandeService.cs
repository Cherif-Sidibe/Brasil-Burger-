using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Models;

namespace VueClient.Services.Impl;

public class CommandeService : ICommandeService
{
    private readonly AppDbContext _context;
    private readonly IPanierService _panierService;

    public CommandeService(AppDbContext context, IPanierService panierService)
    {
        _context = context;
        _panierService = panierService;
    }

    public async Task<int> CreerCommandeAsync(
        TypeLivraisonEnum typeLivraison,
        int? idZone,
        string? adresseLivraison,
        MethodePaiementEnum methodePaiement)
    {
        // Récupérer le panier
        var panier = _panierService.ObtenirPanier();
        if (!panier.Any())
        {
            throw new InvalidOperationException("Le panier est vide");
        }

        // Calculer le montant total
        var sousTotal = _panierService.ObtenirTotal();
        decimal fraisLivraison = 0;

        // Si livraison, récupérer les frais de la zone
        if (typeLivraison == TypeLivraisonEnum.A_LIVRER)
        {
            if (!idZone.HasValue)
            {
                throw new InvalidOperationException("La zone est obligatoire pour une livraison");
            }

            var zone = await _context.Zones.FindAsync(idZone.Value);
            if (zone == null)
            {
                throw new InvalidOperationException("Zone non trouvée");
            }

            fraisLivraison = zone.PrixLivraison;
        }

        var montantTotal = sousTotal + fraisLivraison;

        // Utiliser un ID client par défaut pour les tests (sans authentification)
        const int idClientTest = 1;

        // Créer la commande
        var commande = new Commande
        {
            IdClient = idClientTest,
            DateCommande = DateTime.UtcNow,
            MontantTotal = montantTotal,
            EtatCommande = EtatCommandeEnum.EN_ATTENTE,
            TypeLivraison = typeLivraison,
            AdresseLivraison = adresseLivraison,
            IdZone = idZone
        };

        _context.Commandes.Add(commande);

        // Ne pas sauvegarder encore - on attend d'avoir ajouté tous les détails

        // Créer les détails de commande
        foreach (var item in panier)
        {
            // Convertir le type du panier en TypeArticleEnum
            TypeArticleEnum typeArticle = item.Type.ToLower() switch
            {
                "burger" => TypeArticleEnum.BURGER,
                "menu" => TypeArticleEnum.MENU,
                "complement" => TypeArticleEnum.COMPLEMENT,
                _ => throw new InvalidOperationException($"Type d'article inconnu: {item.Type}")
            };

            var detail = new DetailCommande
            {
                Commande = commande, // Relation directe au lieu de IdCommande
                TypeArticle = typeArticle,
                IdArticle = item.Id,
                Quantite = item.Quantite,
                PrixUnitaire = item.PrixUnitaire,
                SousTotal = item.Total
            };

            _context.DetailCommandes.Add(detail);
        }

        // Créer le paiement
        var paiement = new Paiement
        {
            Commande = commande, // Relation directe au lieu de IdCommande
            DatePaiement = DateTime.UtcNow,
            Montant = montantTotal,
            MethodePaiement = methodePaiement,
            StatutPaiement = StatutPaiementEnum.EN_ATTENTE,
            ReferenceTransaction = $"REF-{DateTime.UtcNow:yyyyMMddHHmmss}"
        };

        _context.Paiements.Add(paiement);

        // Sauvegarder tout en une seule transaction
        await _context.SaveChangesAsync();

        // Vider le panier après la création de la commande
        _panierService.ViderPanier();

        return commande.Id;
    }

    public async Task<Commande?> ObtenirCommandeAsync(int id)
    {
        return await _context.Commandes
            .Include(c => c.Client)
            .Include(c => c.Zone)
            .Include(c => c.DetailCommandes)
            .Include(c => c.Paiement)
            .FirstOrDefaultAsync(c => c.Id == id);
    }

    public async Task<List<Commande>> ObtenirCommandesClientAsync(int idClient)
    {
        return await _context.Commandes
            .Include(c => c.Zone)
            .Include(c => c.Paiement)
            .Where(c => c.IdClient == idClient)
            .OrderByDescending(c => c.DateCommande)
            .ToListAsync();
    }
}
