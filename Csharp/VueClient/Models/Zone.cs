using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("zone")]
public class Zone
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [StringLength(100)]
    [Column("nom")]
    public string Nom { get; set; } = string.Empty;

    [Column("quartiers")]
    public string[]? Quartiers { get; set; }

    [Required]
    [Column("prix_livraison", TypeName = "decimal(10,2)")]
    public decimal PrixLivraison { get; set; }

    [Column("is_archive")]
    public bool IsArchive { get; set; } = false;

    [Column("created_at")]
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("updated_at")]
    public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;

    public virtual ICollection<Commande> Commandes { get; set; } = new List<Commande>();
}
