using VueClient.Models;

namespace VueClient.Services;

public interface ICommandeService
{
    /// <summary>
    /// Crée une nouvelle commande à partir du panier
    /// </summary>
    /// <param name="typeLivraison">Type de livraison (SUR_PLACE, A_EMPORTER, LIVRAISON)</param>
    /// <param name="idZone">ID de la zone (obligatoire si LIVRAISON)</param>
    /// <param name="adresseLivraison">Adresse de livraison (optionnelle)</param>
    /// <param name="methodePaiement">Méthode de paiement</param>
    /// <returns>L'ID de la commande créée</returns>
    Task<int> CreerCommandeAsync(
        TypeLivraisonEnum typeLivraison,
        int? idZone,
        string? adresseLivraison,
        MethodePaiementEnum methodePaiement);

    /// <summary>
    /// Récupère une commande par son ID
    /// </summary>
    Task<Commande?> ObtenirCommandeAsync(int id);

    /// <summary>
    /// Récupère toutes les commandes d'un client
    /// </summary>
    Task<List<Commande>> ObtenirCommandesClientAsync(int idClient);
}
