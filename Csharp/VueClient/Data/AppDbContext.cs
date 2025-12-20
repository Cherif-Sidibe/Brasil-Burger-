using Microsoft.EntityFrameworkCore;
using VueClient.Models;

namespace VueClient.Data;

public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
    {
    }

    public DbSet<User> Users { get; set; }
    public DbSet<Burger> Burgers { get; set; }
    public DbSet<Complement> Complements { get; set; }
    public DbSet<Zone> Zones { get; set; }
    public DbSet<Menu> Menus { get; set; }
    public DbSet<Commande> Commandes { get; set; }
    public DbSet<DetailCommande> DetailCommandes { get; set; }
    public DbSet<Paiement> Paiements { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        // Déclarer les enums PostgreSQL pour EF Core (requis avec Npgsql 8.0+)
        // MapEnum dans Program.cs configure Npgsql, HasPostgresEnum configure EF Core
        modelBuilder.HasPostgresEnum<RoleEnum>("public", "role_enum");
        modelBuilder.HasPostgresEnum<TypeComplementEnum>("public", "type_complement_enum");
        modelBuilder.HasPostgresEnum<EtatCommandeEnum>("public", "etat_commande_enum");
        modelBuilder.HasPostgresEnum<TypeLivraisonEnum>("public", "type_livraison_enum");
        modelBuilder.HasPostgresEnum<TypeArticleEnum>("public", "type_article_enum");
        modelBuilder.HasPostgresEnum<MethodePaiementEnum>("public", "methode_paiement_enum");
        modelBuilder.HasPostgresEnum<StatutPaiementEnum>("public", "statut_paiement_enum");

        modelBuilder.Entity<User>(entity =>
        {
            entity.HasIndex(e => e.Email).IsUnique();
            entity.HasIndex(e => e.Telephone).IsUnique();

            // Le mapping de RoleEnum est géré par HasPostgresEnum ci-dessus

            entity.HasMany(e => e.Commandes)
                .WithOne(e => e.Client)
                .HasForeignKey(e => e.IdClient)
                .OnDelete(DeleteBehavior.Restrict);
        });

        modelBuilder.Entity<Burger>(entity =>
        {
            entity.HasMany(e => e.Menus)
                .WithOne(e => e.Burger)
                .HasForeignKey(e => e.IdBurger)
                .OnDelete(DeleteBehavior.Restrict);
        });

        modelBuilder.Entity<Complement>(entity =>
        {
            // Le mapping est géré par MapEnum dans Program.cs
        });

        modelBuilder.Entity<Zone>(entity =>
        {
            entity.Property(e => e.Quartiers)
                .HasColumnType("text[]");

            entity.HasMany(e => e.Commandes)
                .WithOne(e => e.Zone)
                .HasForeignKey(e => e.IdZone)
                .OnDelete(DeleteBehavior.SetNull);
        });

        modelBuilder.Entity<Menu>(entity =>
        {
            entity.HasOne(e => e.Burger)
                .WithMany(e => e.Menus)
                .HasForeignKey(e => e.IdBurger)
                .OnDelete(DeleteBehavior.Restrict);

            entity.HasOne(e => e.ComplementBoisson)
                .WithMany()
                .HasForeignKey(e => e.IdBoisson)
                .OnDelete(DeleteBehavior.Restrict);

            entity.HasOne(e => e.ComplementFrite)
                .WithMany()
                .HasForeignKey(e => e.IdFrite)
                .OnDelete(DeleteBehavior.Restrict);
        });

        modelBuilder.Entity<Commande>(entity =>
        {
            // Le mapping des enums est géré par MapEnum dans Program.cs

            entity.HasOne(e => e.Client)
                .WithMany(e => e.Commandes)
                .HasForeignKey(e => e.IdClient)
                .OnDelete(DeleteBehavior.Restrict);

            entity.HasOne(e => e.Zone)
                .WithMany(e => e.Commandes)
                .HasForeignKey(e => e.IdZone)
                .OnDelete(DeleteBehavior.SetNull);

            entity.HasOne(e => e.Livreur)
                .WithMany()
                .HasForeignKey(e => e.IdLivreur)
                .OnDelete(DeleteBehavior.SetNull);

            entity.HasMany(e => e.DetailCommandes)
                .WithOne(e => e.Commande)
                .HasForeignKey(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Cascade);

            entity.HasOne(e => e.Paiement)
                .WithOne(e => e.Commande)
                .HasForeignKey<Paiement>(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Restrict);
        });

        modelBuilder.Entity<DetailCommande>(entity =>
        {
            // Le mapping des enums est géré par MapEnum dans Program.cs

            entity.HasOne(e => e.Commande)
                .WithMany(e => e.DetailCommandes)
                .HasForeignKey(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Cascade);
        });

        modelBuilder.Entity<Paiement>(entity =>
        {
            // Le mapping des enums est géré par MapEnum dans Program.cs

            entity.HasIndex(e => e.IdCommande).IsUnique();

            entity.HasOne(e => e.Commande)
                .WithOne(e => e.Paiement)
                .HasForeignKey<Paiement>(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Restrict);
        });
    }
}
