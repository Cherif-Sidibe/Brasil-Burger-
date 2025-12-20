using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Services;
using VueClient.Services.Impl;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseNpgsql("Host=ep-quiet-silence-ae71zpqr-pooler.c-2.us-east-2.aws.neon.tech;Database=Brasil_Burger;Username=neondb_owner;Password=npg_PUa4oqjNxwT9;SSL Mode=Require"));

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
