using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("commande")]
public class Commande
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [Column("id_client")]
    [ForeignKey("Client")]
    public int IdClient { get; set; }

    [Column("date_commande")]
    public DateTime DateCommande { get; set; } = DateTime.UtcNow;

    [Required]
    [Column("montant_total", TypeName = "decimal(10,2)")]
    public decimal MontantTotal { get; set; }

    [Required]
    [StringLength(50)]
    [Column("etat_commande")]
    public string EtatCommande { get; set; } = "EN_ATTENTE";

    [Required]
    [StringLength(50)]
    [Column("type_livraison")]
    public string TypeLivraison { get; set; } = string.Empty;

    [Column("adresse_livraison", TypeName = "text")]
    public string? AdresseLivraison { get; set; }

    [Column("id_zone")]
    [ForeignKey("Zone")]
    public int? IdZone { get; set; }

    [Column("id_livreur")]
    [ForeignKey("Livreur")]
    public int? IdLivreur { get; set; }

    public virtual User Client { get; set; } = null!;
    public virtual Zone? Zone { get; set; }
    public virtual User? Livreur { get; set; }
    public virtual ICollection<DetailCommande> DetailCommandes { get; set; } = new List<DetailCommande>();
    public virtual Paiement? Paiement { get; set; }
}
