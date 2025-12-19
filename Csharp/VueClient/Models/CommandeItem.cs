using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("detail_commande")]
public class DetailCommande
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [Column("id_commande")]
    [ForeignKey("Commande")]
    public int IdCommande { get; set; }

    [Required]
    [StringLength(50)]
    [Column("type_article")]
    public string TypeArticle { get; set; } = string.Empty;

    [Required]
    [Column("id_article")]
    public int IdArticle { get; set; }

    [Required]
    [Column("quantite")]
    public int Quantite { get; set; }

    [Required]
    [Column("prix_unitaire", TypeName = "decimal(10,2)")]
    public decimal PrixUnitaire { get; set; }

    [Required]
    [Column("sous_total", TypeName = "decimal(10,2)")]
    public decimal SousTotal { get; set; }

    public virtual Commande Commande { get; set; } = null!;
}
