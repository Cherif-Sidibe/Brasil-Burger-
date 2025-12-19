using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace VueClient.Models;

[Table("user")]
public class User
{
    [Key]
    [Column("id")]
    public int Id { get; set; }

    [Required]
    [StringLength(100)]
    [Column("prenom")]
    public string Prenom { get; set; } = string.Empty;

    [Required]
    [StringLength(100)]
    [Column("nom")]
    public string Nom { get; set; } = string.Empty;

    [Required]
    [EmailAddress]
    [StringLength(150)]
    [Column("email")]
    public string Email { get; set; } = string.Empty;

    [Required]
    [StringLength(255)]
    [Column("password")]
    public string Password { get; set; } = string.Empty;

    [StringLength(20)]
    [Column("telephone")]
    public string? Telephone { get; set; }

    [Column("adresse", TypeName = "text")]
    public string? Adresse { get; set; }

    [Required]
    [StringLength(20)]
    [Column("role")]
    public string Role { get; set; } = "CLIENT";

    [Column("is_archive")]
    public bool IsArchive { get; set; } = false;

    [Column("created_at")]
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    [Column("updated_at")]
    public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;

    public virtual ICollection<Commande> Commandes { get; set; } = new List<Commande>();
}
