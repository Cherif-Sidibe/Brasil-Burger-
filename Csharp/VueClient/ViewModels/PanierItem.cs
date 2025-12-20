namespace VueClient.ViewModels;

public class PanierItem
{
    public int Id { get; set; }
    public string Type { get; set; } = string.Empty; // "burger", "menu", "complement"
    public string Nom { get; set; } = string.Empty;
    public string? Description { get; set; }
    public string? Image { get; set; }
    public decimal PrixUnitaire { get; set; }
    public int Quantite { get; set; }
    public string? Categorie { get; set; } // Pour affichage (ex: "Burger Signature", "Boisson")

    public decimal Total => PrixUnitaire * Quantite;
}
