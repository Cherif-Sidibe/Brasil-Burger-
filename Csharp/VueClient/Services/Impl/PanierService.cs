using Microsoft.EntityFrameworkCore;
using System.Text.Json;
using VueClient.Data;
using VueClient.Models;
using VueClient.ViewModels;

namespace VueClient.Services.Impl;

public class PanierService : IPanierService
{
    private readonly IHttpContextAccessor _httpContextAccessor;
    private readonly AppDbContext _context;
    private const string PanierSessionKey = "Panier";

    public PanierService(IHttpContextAccessor httpContextAccessor, AppDbContext context)
    {
        _httpContextAccessor = httpContextAccessor;
        _context = context;
    }

    private ISession Session => _httpContextAccessor.HttpContext!.Session;

    private List<PanierItem> ObtenirPanierSession()
    {
        var panierJson = Session.GetString(PanierSessionKey);
        return string.IsNullOrEmpty(panierJson)
            ? new List<PanierItem>()
            : JsonSerializer.Deserialize<List<PanierItem>>(panierJson) ?? new List<PanierItem>();
    }

    private void SauvegarderPanierSession(List<PanierItem> panier)
    {
        var panierJson = JsonSerializer.Serialize(panier);
        Session.SetString(PanierSessionKey, panierJson);
    }

    public async Task AjouterArticle(int id, string type)
    {
        var panier = ObtenirPanierSession();
        var itemExistant = panier.FirstOrDefault(p => p.Id == id && p.Type == type);

        if (itemExistant != null)
        {
            itemExistant.Quantite++;
        }
        else
        {
            PanierItem? nouvelItem = null;

            switch (type.ToLower())
            {
                case "burger":
                    var burger = await _context.Burgers.FindAsync(id);
                    if (burger != null && !burger.IsArchive)
                    {
                        nouvelItem = new PanierItem
                        {
                            Id = burger.Id,
                            Type = "burger",
                            Nom = burger.Nom,
                            Description = burger.Description,
                            Image = burger.Image,
                            PrixUnitaire = burger.Prix,
                            Quantite = 1,
                            Categorie = "Burger"
                        };
                    }
                    break;

                case "menu":
                    var menu = await _context.Menus
                        .Include(m => m.Burger)
                        .Include(m => m.ComplementBoisson)
                        .Include(m => m.ComplementFrite)
                        .FirstOrDefaultAsync(m => m.Id == id);

                    if (menu != null && !menu.IsArchive)
                    {
                        nouvelItem = new PanierItem
                        {
                            Id = menu.Id,
                            Type = "menu",
                            Nom = menu.Nom,
                            Description = $"{menu.Burger.Nom} + {menu.ComplementBoisson.Nom} + {menu.ComplementFrite.Nom}",
                            Image = menu.Image,
                            PrixUnitaire = menu.Prix,
                            Quantite = 1,
                            Categorie = "Menu"
                        };
                    }
                    break;

                case "complement":
                    var complement = await _context.Complements.FindAsync(id);
                    if (complement != null && !complement.IsArchive)
                    {
                        nouvelItem = new PanierItem
                        {
                            Id = complement.Id,
                            Type = "complement",
                            Nom = complement.Nom,
                            Description = complement.Description,
                            Image = complement.Image,
                            PrixUnitaire = complement.Prix,
                            Quantite = 1,
                            Categorie = complement.TypeComplement == TypeComplementEnum.BOISSON ? "Boisson" : "Frites"
                        };
                    }
                    break;
            }

            if (nouvelItem != null)
            {
                panier.Add(nouvelItem);
            }
        }

        SauvegarderPanierSession(panier);
    }

    public List<PanierItem> ObtenirPanier()
    {
        return ObtenirPanierSession();
    }

    public void ModifierQuantite(int id, string type, int nouvelleQuantite)
    {
        var panier = ObtenirPanierSession();
        var item = panier.FirstOrDefault(p => p.Id == id && p.Type == type);

        if (item != null)
        {
            if (nouvelleQuantite <= 0)
            {
                panier.Remove(item);
            }
            else
            {
                item.Quantite = nouvelleQuantite;
            }

            SauvegarderPanierSession(panier);
        }
    }

    public void RetirerArticle(int id, string type)
    {
        var panier = ObtenirPanierSession();
        var item = panier.FirstOrDefault(p => p.Id == id && p.Type == type);

        if (item != null)
        {
            panier.Remove(item);
            SauvegarderPanierSession(panier);
        }
    }

    public void ViderPanier()
    {
        Session.Remove(PanierSessionKey);
    }

    public int ObtenirNombreArticles()
    {
        var panier = ObtenirPanierSession();
        return panier.Sum(p => p.Quantite);
    }

    public decimal ObtenirTotal()
    {
        var panier = ObtenirPanierSession();
        return panier.Sum(p => p.Total);
    }
}
