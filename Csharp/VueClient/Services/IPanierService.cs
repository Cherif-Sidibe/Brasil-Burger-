using VueClient.ViewModels;

namespace VueClient.Services;

public interface IPanierService
{
    Task AjouterArticle(int id, string type);
    List<PanierItem> ObtenirPanier();
    void ModifierQuantite(int id, string type, int nouvelleQuantite);
    void RetirerArticle(int id, string type);
    void ViderPanier();
    int ObtenirNombreArticles();
    decimal ObtenirTotal();
}
