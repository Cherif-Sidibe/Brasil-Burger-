using VueClient.Models;

namespace VueClient.Services;

public interface ICatalogueService
{
    Task<List<Burger>> GetBurgersActifs();
    Task<List<Menu>> GetMenusActifs();
    Task<List<Complement>> GetComplementsActifs();
    Task<Burger?> GetBurgerDetails(int id);
    Task<Menu?> GetMenuDetails(int id);
    decimal CalculerPrixMenu(int idBurger, int idBoisson, int idFrite);
}
