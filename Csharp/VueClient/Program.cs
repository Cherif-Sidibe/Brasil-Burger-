using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Services;
using VueClient.Services.Impl;
using Npgsql;
using Npgsql.NameTranslation;
using VueClient.Models;

var builder = WebApplication.CreateBuilder(args);

// Configuration PostgreSQL avec GlobalTypeMapper pour Npgsql 7.x
var connectionString = "Host=ep-quiet-silence-ae71zpqr-pooler.c-2.us-east-2.aws.neon.tech;Database=Brasil_Burger;Username=neondb_owner;Password=npg_PUa4oqjNxwT9;SSL Mode=Require;Trust Server Certificate=true";

// Utiliser un traducteur qui ne modifie pas les noms (les garde en MAJUSCULES)
var nameTranslator = new NpgsqlNullNameTranslator();

// Mapper les enums C# vers les enums PostgreSQL natifs (Npgsql 7.x style)
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeComplementEnum>("type_complement_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<EtatCommandeEnum>("etat_commande_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeLivraisonEnum>("type_livraison_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeArticleEnum>("type_article_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<MethodePaiementEnum>("methode_paiement_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<StatutPaiementEnum>("statut_paiement_enum", nameTranslator);

builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseNpgsql(connectionString));

// Configuration de la session
builder.Services.AddDistributedMemoryCache();
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromHours(2);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

// Services
builder.Services.AddHttpContextAccessor();
builder.Services.AddScoped<ICatalogueService, CatalogueService>();
builder.Services.AddScoped<IPanierService, PanierService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();

builder.Services.AddControllersWithViews();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
    app.UseHsts();
}

// Désactiver la redirection HTTPS pour Render (qui gère HTTPS en amont)
// app.UseHttpsRedirection();
app.UseRouting();

app.UseSession();
app.UseAuthorization();

app.MapStaticAssets();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Catalogue}/{action=Index}/{id?}")
    .WithStaticAssets();


app.Run();
