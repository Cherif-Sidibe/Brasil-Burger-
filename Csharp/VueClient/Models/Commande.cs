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
    [Column("etat_commande")]
    public EtatCommandeEnum EtatCommande { get; set; } = EtatCommandeEnum.EN_ATTENTE;

    [Required]
    [Column("type_livraison")]
    public TypeLivraisonEnum TypeLivraison { get; set; } = TypeLivraisonEnum.SUR_PLACE;

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
