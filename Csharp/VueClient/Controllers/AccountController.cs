using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using VueClient.Data;
using VueClient.Models;
using VueClient.Services;
using System.Threading.Tasks;
using System.ComponentModel.DataAnnotations;

namespace VueClient.Controllers
{
    public class AccountController : Controller
    {
        private readonly AppDbContext _db;
        private readonly CustomAuthService _authService;

        public AccountController(AppDbContext db, CustomAuthService authService)
        {
            _db = db;
            _authService = authService;
        }

        [HttpGet]
        public IActionResult Login(string? returnUrl = null)
        {
            ViewData["ReturnUrl"] = returnUrl ?? string.Empty;
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Login(string emailConnexion, string passwordConnexion, bool rememberMe = false, string? returnUrl = null)
        {
            ViewData["ReturnUrl"] = returnUrl ?? string.Empty;
            
            // Validation des champs
            if (string.IsNullOrWhiteSpace(emailConnexion))
            {
                ModelState.AddModelError("emailConnexion", "L'email est obligatoire.");
            }
            else if (!new EmailAddressAttribute().IsValid(emailConnexion))
            {
                ModelState.AddModelError("emailConnexion", "L'email n'est pas valide.");
            }
            
            if (string.IsNullOrWhiteSpace(passwordConnexion))
            {
                ModelState.AddModelError("passwordConnexion", "Le mot de passe est obligatoire.");
            }

            // Si des erreurs de validation existent, retourner la vue
            if (!ModelState.IsValid)
            {
                return View();
            }

            // Tentative d'authentification
            var user = await _authService.AuthenticateAsync(emailConnexion.Trim().ToLower(), passwordConnexion);
            if (user == null)
            {
                ModelState.AddModelError(string.Empty, "Email ou mot de passe incorrect.");
                return View();
            }

            // Vérifier si l'utilisateur est archivé
            if (user.IsArchive)
            {
                ModelState.AddModelError(string.Empty, "Ce compte a été désactivé.");
                return View();
            }

            // Vérifier que l'utilisateur est un CLIENT uniquement
            if (user.Role != RoleEnum.CLIENT)
            {
                ModelState.AddModelError(string.Empty, "Seuls les clients peuvent se connecter via cette page.");
                return View();
            }

            // Connexion réussie
            await _authService.SignInAsync(user, rememberMe);
            
            // Redirection selon le returnUrl ou vers le catalogue
            if (!string.IsNullOrEmpty(returnUrl) && Url.IsLocalUrl(returnUrl))
            {
                return Redirect(returnUrl);
            }
            
            return RedirectToAction("Index", "Catalogue");
        }

        [HttpGet]
        public IActionResult Register()
        {
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> Register(string nom, string prenom, string telephone, string adresse, string zone, string email, string password, string confirmPassword)
        {
            // Validation des champs obligatoires
            if (string.IsNullOrWhiteSpace(nom))
            {
                ModelState.AddModelError("nom", "Le nom est obligatoire.");
            }
            if (string.IsNullOrWhiteSpace(prenom))
            {
                ModelState.AddModelError("prenom", "Le prénom est obligatoire.");
            }
            if (string.IsNullOrWhiteSpace(telephone))
            {
                ModelState.AddModelError("telephone", "Le téléphone est obligatoire.");
            }
            if (string.IsNullOrWhiteSpace(adresse))
            {
                ModelState.AddModelError("adresse", "L'adresse est obligatoire.");
            }
            if (string.IsNullOrWhiteSpace(email))
            {
                ModelState.AddModelError("email", "L'email est obligatoire.");
            }
            else if (!new EmailAddressAttribute().IsValid(email))
            {
                ModelState.AddModelError("email", "L'email n'est pas valide.");
            }
            if (string.IsNullOrWhiteSpace(password))
            {
                ModelState.AddModelError("password", "Le mot de passe est obligatoire.");
            }
            else if (password.Length < 8)
            {
                ModelState.AddModelError("password", "Le mot de passe doit contenir au moins 8 caractères.");
            }
            if (string.IsNullOrWhiteSpace(confirmPassword))
            {
                ModelState.AddModelError("confirmPassword", "La confirmation du mot de passe est obligatoire.");
            }

            // Validation de la correspondance des mots de passe
            if (!string.IsNullOrWhiteSpace(password) && !string.IsNullOrWhiteSpace(confirmPassword) && password != confirmPassword)
            {
                ModelState.AddModelError("confirmPassword", "Les mots de passe ne correspondent pas.");
            }

            // Vérification de l'unicité de l'email
            if (!string.IsNullOrWhiteSpace(email) && await _db.Users.AnyAsync(u => u.Email == email))
            {
                ModelState.AddModelError("email", "Cet email est déjà utilisé.");
            }

            // Vérification de l'unicité du téléphone
            if (!string.IsNullOrWhiteSpace(telephone) && await _db.Users.AnyAsync(u => u.Telephone == telephone))
            {
                ModelState.AddModelError("telephone", "Ce numéro de téléphone est déjà utilisé.");
            }

            // Si des erreurs existent, retourner la vue avec les erreurs
            if (!ModelState.IsValid)
            {
                return View();
            }

            // Création de l'utilisateur
            var user = new User
            {
                Nom = nom.Trim(),
                Prenom = prenom.Trim(),
                Telephone = telephone.Trim(),
                Adresse = adresse.Trim(),
                Email = email.Trim().ToLower(),
                Password = password,
                Role = RoleEnum.CLIENT
            };
            
            _db.Users.Add(user);
            await _db.SaveChangesAsync();
            await _authService.SignInAsync(user, false);
            return RedirectToAction("Index", "Catalogue");
        }

        [HttpPost]
        public async Task<IActionResult> Logout()
        {
            await _authService.SignOutAsync();
            return RedirectToAction("Index", "Catalogue");
        }
    }
}
