using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("menu")]
public class Menu
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [StringLength(100)]
    [Column("nom")]
    public string Nom { get; set; } = string.Empty;

    [Required]
    [Column("prix", TypeName = "decimal(10,2)")]
    public decimal Prix { get; set; }

    [Column("description", TypeName = "text")]
    public string? Description { get; set; }

    [StringLength(255)]
    [Column("image")]
    public string? Image { get; set; }

    [Required]
    [Column("id_burger")]
    [ForeignKey("Burger")]
    public int IdBurger { get; set; }

    [Required]
    [Column("id_boisson")]
    [ForeignKey("ComplementBoisson")]
    public int IdBoisson { get; set; }

    [Required]
    [Column("id_frite")]
    [ForeignKey("ComplementFrite")]
    public int IdFrite { get; set; }

    [Column("is_archive")]
    public bool IsArchive { get; set; } = false;

    [Column("created_at")]
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("updated_at")]
    public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;

    public virtual Burger Burger { get; set; } = null!;
    public virtual Complement ComplementBoisson { get; set; } = null!;
    public virtual Complement ComplementFrite { get; set; } = null!;
}
