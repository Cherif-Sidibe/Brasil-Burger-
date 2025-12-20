using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Services;
using VueClient.Services.Impl;
using Npgsql;
using Npgsql.NameTranslation;
using VueClient.Models;
using Microsoft.AspNetCore.Authentication.Cookies;

var builder = WebApplication.CreateBuilder(args);

var connectionString = "Host=ep-quiet-silence-ae71zpqr-pooler.c-2.us-east-2.aws.neon.tech;Database=Brasil_Burger;Username=neondb_owner;Password=npg_PUa4oqjNxwT9;SSL Mode=Require;Trust Server Certificate=true";

var nameTranslator = new NpgsqlNullNameTranslator();
NpgsqlConnection.GlobalTypeMapper.MapEnum<RoleEnum>("role_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeComplementEnum>("type_complement_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<EtatCommandeEnum>("etat_commande_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeLivraisonEnum>("type_livraison_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<TypeArticleEnum>("type_article_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<MethodePaiementEnum>("methode_paiement_enum", nameTranslator);
NpgsqlConnection.GlobalTypeMapper.MapEnum<StatutPaiementEnum>("statut_paiement_enum", nameTranslator);

builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseNpgsql(connectionString));

builder.Services.AddDistributedMemoryCache();
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromHours(2);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

builder.Services.AddHttpContextAccessor();
builder.Services.AddScoped<ICatalogueService, CatalogueService>();
builder.Services.AddScoped<IPanierService, PanierService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();
builder.Services.AddScoped<CustomAuthService>();

builder.Services.AddAuthentication(CookieAuthenticationDefaults.AuthenticationScheme)
    .AddCookie(options =>
    {
        options.LoginPath = "/Account/Login";
        options.LogoutPath = "/Account/Logout";
        options.AccessDeniedPath = "/Account/Login";
    });

builder.Services.AddControllersWithViews();

var app = builder.Build();

if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}
app.UseRouting();

app.UseSession();
app.UseAuthentication();
app.UseAuthorization();

app.MapStaticAssets();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Catalogue}/{action=Index}/{id?}")
    .WithStaticAssets();


app.Run();
