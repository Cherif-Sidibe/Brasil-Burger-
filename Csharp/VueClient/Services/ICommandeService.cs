using VueClient.Models;

namespace VueClient.Services;

public interface ICommandeService
{
    Task<int> CreerCommandeAsync(
        int idClient,
        TypeLivraisonEnum typeLivraison,
        int? idZone,
        string? adresseLivraison,
        MethodePaiementEnum methodePaiement);

    Task<Commande?> ObtenirCommandeAsync(int id);

    Task<List<Commande>> ObtenirCommandesClientAsync(int idClient);
}
