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

        modelBuilder.Entity<User>(entity =>
        {
            entity.Property(e => e.Role).HasConversion<string>();
            entity.HasIndex(e => e.Email).IsUnique();
            entity.HasIndex(e => e.Telephone).IsUnique();

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
            entity.Property(e => e.TypeComplement).HasConversion<string>();
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
            entity.Property(e => e.EtatCommande).HasConversion<string>();
            entity.Property(e => e.TypeLivraison).HasConversion<string>();

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
            entity.Property(e => e.TypeArticle).HasConversion<string>();

            entity.HasOne(e => e.Commande)
                .WithMany(e => e.DetailCommandes)
                .HasForeignKey(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Cascade);
        });

        modelBuilder.Entity<Paiement>(entity =>
        {
            entity.Property(e => e.MethodePaiement).HasConversion<string>();
            entity.Property(e => e.StatutPaiement).HasConversion<string>();

            entity.HasIndex(e => e.IdCommande).IsUnique();

            entity.HasOne(e => e.Commande)
                .WithOne(e => e.Paiement)
                .HasForeignKey<Paiement>(e => e.IdCommande)
                .OnDelete(DeleteBehavior.Restrict);
        });
    }
}
