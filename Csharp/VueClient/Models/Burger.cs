using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("burger")]
public class Burger
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

    [Column("is_archive")]
    public bool IsArchive { get; set; } = false;

    [Column("created_at")]
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("updated_at")]
    public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;

    public virtual ICollection<Menu> Menus { get; set; } = new List<Menu>();
}
