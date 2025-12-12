package sidibe.cherif;

import sidibe.cherif.factory.ServiceFactory;
import sidibe.cherif.entity.*;
import sidibe.cherif.service.BurgerService;
import sidibe.cherif.views.MainViews;

import java.util.List;

public class Main {
    public static void main(String[] args) {
        ServiceFactory factory = ServiceFactory.getInstance();
        BurgerService burgerService = factory.getBurgerService();
        
        MainViews views = new MainViews();
        
        System.out.println("╔══════════════════════════════════════╗");
        System.out.println("║   BIENVENUE CHEZ BRASIL BURGER       ║");
        System.out.println("╚══════════════════════════════════════╝\n");
        
        boolean continuer = true;
        
        while (continuer) {
            int choix = views.afficherMenuPrincipal();
            
            switch (choix) {
                case 1:
                    gererBurgers(burgerService, views);
                    break;
                case 2:
                    System.out.println("Gestion des Compléments sélectionnée");
                    break;
                case 3:
                    System.out.println("Gestion des Menus sélectionnée");
                    break;
                case 4:
                    System.out.println("Gestion des Utilisateurs sélectionnée");
                    break;
                case 5:
                    System.out.println("Gestion des Zones de Livraison sélectionnée");
                    break;
                case 6:
                    System.out.println("\n Merci d'avoir utilisé Brasil Burger. À bientôt !");
                    continuer = false;
                    break;
                default:
                    System.out.println("Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void gererBurgers(BurgerService burgerService, MainViews views) {
        boolean retour = false;
        
        while (!retour) {
            int choix = views.gererBurgers();
            
            switch (choix) {
                case 1:
                    ajouterBurger(burgerService, views);
                    break;
                case 2:
                    listerBurgers(burgerService, views);
                    break;
                case 3:
                    modifierBurger(burgerService, views);
                    break;
                case 4:
                    archiverBurger(burgerService, views);
                    break;
                case 5:
                    retour = true;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void ajouterBurger(BurgerService burgerService, MainViews views) {
        System.out.println("\n=== Ajouter un Burger ===");
        Burger burger = views.saisirBurger();
        
        if (burger != null) {
            int id = burgerService.creerBurger(burger);
            if (id > 0) {
                System.out.println(" Burger ajouté avec succès ! (ID: " + id + ")\n");
            } else {
                System.out.println(" Erreur lors de l'ajout du burger.\n");
            }
        }
    }
    
    private static void listerBurgers(BurgerService burgerService, MainViews views) {
        System.out.println("\n=== Liste des Burgers ===");
        List<Burger> burgers = burgerService.listerBurgers();
        
        if (burgers == null || burgers.isEmpty()) {
            System.out.println("📋 Aucun burger disponible.\n");
        } else {
            views.afficherBurgers(burgers);
        }
    }
    
    private static void modifierBurger(BurgerService burgerService, MainViews views) {
        System.out.println("\n=== Modifier un Burger ===");
        
        List<Burger> burgers = burgerService.listerBurgers();
        if (burgers == null || burgers.isEmpty()) {
            System.out.println("📋 Aucun burger disponible à modifier.\n");
            return;
        }
        
        views.afficherBurgers(burgers);
        
        int id = views.demanderID("ID du burger à modifier");
        Burger burger = burgerService.getBurgerById(id);
        
        if (burger == null) {
            System.out.println("❌ Burger introuvable.\n");
            return;
        }
        
        System.out.println("\nBurger actuel: " + burger.getNom() + " - " + burger.getPrix() + " FCFA");
        System.out.println("Entrez les nouvelles informations (laissez vide pour conserver)\n");
        
        Burger modifications = views.saisirBurger();
        if (modifications != null) {
            modifications.setId(id);
            try {
                burgerService.modifierBurger(modifications);
                System.out.println("✅ Burger modifié avec succès !\n");
            } catch (Exception e) {
                System.out.println("❌ Erreur lors de la modification du burger: " + e.getMessage() + "\n");
            }
        }
    }
    
    private static void archiverBurger(BurgerService burgerService, MainViews views) {
        System.out.println("\n=== Archiver un Burger ===");
        
        List<Burger> burgers = burgerService.listerBurgers();
        if (burgers == null || burgers.isEmpty()) {
            System.out.println("📋 Aucun burger disponible à archiver.\n");
            return;
        }
        
        views.afficherBurgers(burgers);
        
        int id = views.demanderID("ID du burger à archiver");
        Burger burger = burgerService.getBurgerById(id);
        
        if (burger == null) {
            System.out.println("❌ Burger introuvable.\n");
            return;
        }
        
        try {
            burgerService.archiverBurger(id);
            System.out.println("✅ Burger '" + burger.getNom() + "' archivé avec succès !\n");
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage du burger: " + e.getMessage() + "\n");
        }
    }
}