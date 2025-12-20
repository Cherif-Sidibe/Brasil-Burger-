using VueClient.Models;

namespace VueClient.ViewModels;

public class CatalogueViewModel
{
    public List<Burger> Burgers { get; set; } = new();
    public List<Menu> Menus { get; set; } = new();
    public List<Complement> Boissons { get; set; } = new();
    public List<Complement> Frites { get; set; } = new();
    public string FiltreActif { get; set; } = "all";
}
