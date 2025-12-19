using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("paiement")]
public class Paiement
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [Column("id_commande")]
    [ForeignKey("Commande")]
    public int IdCommande { get; set; }

    [Column("date_paiement")]
    public DateTime DatePaiement { get; set; } = DateTime.UtcNow;

    [Required]
    [Column("montant", TypeName = "decimal(10,2)")]
    public decimal Montant { get; set; }

    [Required]
    [Column("methode_paiement")]
    public MethodePaiementEnum MethodePaiement { get; set; }

    [Required]
    [Column("statut_paiement")]
    public StatutPaiementEnum StatutPaiement { get; set; } = StatutPaiementEnum.EN_ATTENTE;

    [StringLength(100)]
    [Column("reference_transaction")]
    public string? ReferenceTransaction { get; set; }

    public virtual Commande Commande { get; set; } = null!;
}
