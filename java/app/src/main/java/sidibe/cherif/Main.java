package sidibe.cherif;

import sidibe.cherif.factory.ServiceFactory;
import sidibe.cherif.entity.*;
import sidibe.cherif.service.BurgerService;
import sidibe.cherif.service.ComplementService;
import sidibe.cherif.service.MenuService;
import sidibe.cherif.service.UserService;
import sidibe.cherif.service.ZoneService;
import sidibe.cherif.views.MainViews;

import java.util.List;

public class Main {
    public static void main(String[] args) {
        ServiceFactory factory = ServiceFactory.getInstance();
        BurgerService burgerService = factory.getBurgerService();
        ComplementService complementService = factory.getComplementService();
        MenuService menuService = factory.getMenuService();
        UserService userService = factory.getUserService();
        ZoneService zoneService = factory.getZoneService();
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
                    gererComplements(complementService, views);
                    break;
                case 3:
                    gererMenus(menuService, burgerService, complementService, views);
                    break;
                case 4:
                    gererUsers(userService, views);
                    break;
                case 5:
                    gererZones(zoneService, views);
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
    
    private static void gererComplements(ComplementService complementService, MainViews views) {
        boolean retour = false;
        
        while (!retour) {
            int choix = views.gererComplements();
            
            switch (choix) {
                case 1:
                    ajouterComplement(complementService, views);
                    break;
                case 2:
                    listerComplements(complementService, views);
                    break;
                case 3:
                    modifierComplement(complementService, views);
                    break;
                case 4:
                    archiverComplement(complementService, views);
                    break;
                case 5:
                    retour = true;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void ajouterComplement(ComplementService complementService, MainViews views) {
        System.out.println("\n=== Ajouter un Complément ===");
        Complement complement = views.saisirComplement();
        
        if (complement != null) {
            int id = complementService.creerComplement(complement);
            if (id > 0) {
                System.out.println("✅ Complément ajouté avec succès ! (ID: " + id + ")\n");
            } else {
                System.out.println("❌ Erreur lors de l'ajout du complément.\n");
            }
        }
    }
    
    private static void listerComplements(ComplementService complementService, MainViews views) {
        System.out.println("\n=== Liste des Compléments ===");
        List<Complement> complements = complementService.listerComplements();
        
        if (complements == null || complements.isEmpty()) {
            System.out.println("📋 Aucun complément disponible.\n");
        } else {
            views.afficherComplements(complements);
        }
    }
    
    private static void modifierComplement(ComplementService complementService, MainViews views) {
        System.out.println("\n=== Modifier un Complément ===");
        
        List<Complement> complements = complementService.listerComplements();
        if (complements == null || complements.isEmpty()) {
            System.out.println("📋 Aucun complément disponible à modifier.\n");
            return;
        }
        
        views.afficherComplements(complements);
        
        int id = views.demanderID("ID du complément à modifier");
        Complement complement = complementService.getComplementById(id);
        
        if (complement == null) {
            System.out.println("❌ Complément introuvable.\n");
            return;
        }
        
        System.out.println("\nComplément actuel: " + complement.getNom() + " - " + complement.getPrix() + " FCFA");
        System.out.println("Entrez les nouvelles informations (laissez vide pour conserver)\n");
        
        Complement modifications = views.saisirComplement();
        if (modifications != null) {
            modifications.setId(id);
            try {
                complementService.modifierComplement(modifications);
                System.out.println("✅ Complément modifié avec succès !\n");
            } catch (Exception e) {
                System.out.println("❌ Erreur lors de la modification du complément: " + e.getMessage() + "\n");
            }
        }
    }
    
    private static void archiverComplement(ComplementService complementService, MainViews views) {
        System.out.println("\n=== Archiver un Complément ===");
        
        List<Complement> complements = complementService.listerComplements();
        if (complements == null || complements.isEmpty()) {
            System.out.println("📋 Aucun complément disponible à archiver.\n");
            return;
        }
        
        views.afficherComplements(complements);
        
        int id = views.demanderID("ID du complément à archiver");
        Complement complement = complementService.getComplementById(id);
        
        if (complement == null) {
            System.out.println("❌ Complément introuvable.\n");
            return;
        }
        
        try {
            complementService.archiverComplement(id);
            System.out.println("✅ Complément '" + complement.getNom() + "' archivé avec succès !\n");
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage du complément: " + e.getMessage() + "\n");
        }
    }
    
    private static void gererMenus(MenuService menuService, BurgerService burgerService, ComplementService complementService, MainViews views) {
        boolean retour = false;
        
        while (!retour) {
            int choix = views.gererMenus();
            
            switch (choix) {
                case 1:
                    ajouterMenu(menuService, views);
                    break;
                case 2:
                    listerMenus(menuService, burgerService, complementService, views);
                    break;
                case 3:
                    modifierMenu(menuService, burgerService, complementService, views);
                    break;
                case 4:
                    archiverMenu(menuService, burgerService, complementService, views);
                    break;
                case 5:
                    retour = true;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void ajouterMenu(MenuService menuService, MainViews views) {
        System.out.println("\n=== Ajouter un Menu ===");
        Menu menu = views.saisirMenu();
        
        if (menu != null) {
            int id = menuService.creerMenu(menu);
            if (id > 0) {
                System.out.println("✅ Menu ajouté avec succès ! (ID: " + id + ")\n");
            } else {
                System.out.println("❌ Erreur lors de l'ajout du menu.\n");
            }
        }
    }
    
    private static void listerMenus(MenuService menuService, BurgerService burgerService, ComplementService complementService, MainViews views) {
        System.out.println("\n=== Liste des Menus ===");
        List<Menu> menus = menuService.listerMenus();
        
        if (menus == null || menus.isEmpty()) {
            System.out.println("📋 Aucun menu disponible.\n");
        } else {
            views.afficherMenus(menus, burgerService, complementService);
        }
    }
    
    private static void modifierMenu(MenuService menuService, BurgerService burgerService, ComplementService complementService, MainViews views) {
        System.out.println("\n=== Modifier un Menu ===");
        
        List<Menu> menus = menuService.listerMenus();
        if (menus == null || menus.isEmpty()) {
            System.out.println("📋 Aucun menu disponible à modifier.\n");
            return;
        }
        
        views.afficherMenus(menus, burgerService, complementService);
        
        int id = views.demanderID("ID du menu à modifier");
        Menu menu = menuService.getMenuById(id);
        
        if (menu == null) {
            System.out.println("❌ Menu introuvable.\n");
            return;
        }
        
        System.out.println("\nMenu actuel: " + menu.getNom() + " - " + menu.getPrix() + " FCFA");
        System.out.println("Entrez les nouvelles informations (laissez vide pour conserver)\n");
        
        Menu modifications = views.saisirMenu();
        if (modifications != null) {
            modifications.setId(id);
            try {
                menuService.modifierMenu(modifications);
                System.out.println("✅ Menu modifié avec succès !\n");
            } catch (Exception e) {
                System.out.println("❌ Erreur lors de la modification du menu: " + e.getMessage() + "\n");
            }
        }
    }
    
    private static void archiverMenu(MenuService menuService, BurgerService burgerService, ComplementService complementService, MainViews views) {
        System.out.println("\n=== Archiver un Menu ===");
        
        List<Menu> menus = menuService.listerMenus();
        if (menus == null || menus.isEmpty()) {
            System.out.println("📋 Aucun menu disponible à archiver.\n");
            return;
        }
        
        views.afficherMenus(menus, burgerService, complementService);
        
        int id = views.demanderID("ID du menu à archiver");
        Menu menu = menuService.getMenuById(id);
        
        if (menu == null) {
            System.out.println("❌ Menu introuvable.\n");
            return;
        }
        
        try {
            menuService.archiverMenu(id);
            System.out.println("✅ Menu '" + menu.getNom() + "' archivé avec succès !\n");
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage du menu: " + e.getMessage() + "\n");
        }
    }
    
    private static void gererUsers(UserService userService, MainViews views) {
        boolean retour = false;
        
        while (!retour) {
            int choix = views.gererUtilisateurs();
            
            switch (choix) {
                case 1:
                    ajouterUser(userService, views);
                    break;
                case 2:
                    listerUsers(userService, views);
                    break;
                case 3:
                    modifierUser(userService, views);
                    break;
                case 4:
                    supprimerUser(userService, views);
                    break;
                case 5:
                    retour = true;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void ajouterUser(UserService userService, MainViews views) {
        System.out.println("\n=== Ajouter un Utilisateur ===");
        User user = views.saisirUser();
        
        if (user != null) {
            int id = userService.creerUser(user);
            if (id > 0) {
                System.out.println("✅ Utilisateur ajouté avec succès ! (ID: " + id + ")\n");
            } else {
                System.out.println("❌ Erreur lors de l'ajout de l'utilisateur.\n");
            }
        }
    }
    
    private static void listerUsers(UserService userService, MainViews views) {
        System.out.println("\n=== Liste des Utilisateurs ===");
        List<User> users = userService.listerUsers();
        
        if (users == null || users.isEmpty()) {
            System.out.println("📋 Aucun utilisateur disponible.\n");
        } else {
            views.afficherUsers(users);
        }
    }
    
    private static void modifierUser(UserService userService, MainViews views) {
        System.out.println("\n=== Modifier un Utilisateur ===");
        
        List<User> users = userService.listerUsers();
        if (users == null || users.isEmpty()) {
            System.out.println("📋 Aucun utilisateur disponible à modifier.\n");
            return;
        }
        
        views.afficherUsers(users);
        
        int id = views.demanderID("ID de l'utilisateur à modifier");
        User user = userService.getUserById(id);
        
        if (user == null) {
            System.out.println("❌ Utilisateur introuvable.\n");
            return;
        }
        
        System.out.println("\nUtilisateur actuel: " + user.getPrenom() + " " + user.getNom() + " (" + user.getEmail() + ")");
        System.out.println("Entrez les nouvelles informations (laissez vide pour conserver)\n");
        
        User modifications = views.saisirUser();
        if (modifications != null) {
            modifications.setId(id);
            try {
                userService.modifierUser(modifications);
                System.out.println("✅ Utilisateur modifié avec succès !\n");
            } catch (Exception e) {
                System.out.println("❌ Erreur lors de la modification de l'utilisateur: " + e.getMessage() + "\n");
            }
        }
    }
    
    private static void supprimerUser(UserService userService, MainViews views) {
        System.out.println("\n=== Supprimer un Utilisateur ===");
        
        List<User> users = userService.listerUsers();
        if (users == null || users.isEmpty()) {
            System.out.println("📋 Aucun utilisateur disponible à supprimer.\n");
            return;
        }
        
        views.afficherUsers(users);
        
        int id = views.demanderID("ID de l'utilisateur à supprimer");
        User user = userService.getUserById(id);
        
        if (user == null) {
            System.out.println("❌ Utilisateur introuvable.\n");
            return;
        }
        
        try {
            userService.supprimerUser(id);
            System.out.println("✅ Utilisateur '" + user.getPrenom() + " " + user.getNom() + "' supprimé avec succès !\n");
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la suppression de l'utilisateur: " + e.getMessage() + "\n");
        }
    }
    
    private static void gererZones(ZoneService zoneService, MainViews views) {
        boolean retour = false;
        
        while (!retour) {
            int choix = views.gererZonesLivraison();
            
            switch (choix) {
                case 1:
                    ajouterZone(zoneService, views);
                    break;
                case 2:
                    listerZones(zoneService, views);
                    break;
                case 3:
                    modifierZone(zoneService, views);
                    break;
                case 4:
                    supprimerZone(zoneService, views);
                    break;
                case 5:
                    retour = true;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
    
    private static void ajouterZone(ZoneService zoneService, MainViews views) {
        System.out.println("\n=== Ajouter une Zone de Livraison ===");
        Zone zone = views.saisirZone();
        
        if (zone != null) {
            int id = zoneService.creerZone(zone);
            if (id > 0) {
                System.out.println("✅ Zone ajoutée avec succès ! (ID: " + id + ")\n");
            } else {
                System.out.println("❌ Erreur lors de l'ajout de la zone.\n");
            }
        }
    }
    
    private static void listerZones(ZoneService zoneService, MainViews views) {
        System.out.println("\n=== Liste des Zones de Livraison ===");
        List<Zone> zones = zoneService.listerZones();
        
        if (zones == null || zones.isEmpty()) {
            System.out.println("📋 Aucune zone disponible.\n");
        } else {
            views.afficherZones(zones);
        }
    }
    
    private static void modifierZone(ZoneService zoneService, MainViews views) {
        System.out.println("\n=== Modifier une Zone de Livraison ===");
        
        List<Zone> zones = zoneService.listerZones();
        if (zones == null || zones.isEmpty()) {
            System.out.println("📋 Aucune zone disponible à modifier.\n");
            return;
        }
        
        views.afficherZones(zones);
        
        int id = views.demanderID("ID de la zone à modifier");
        Zone zone = zoneService.getZoneById(id);
        
        if (zone == null) {
            System.out.println("❌ Zone introuvable.\n");
            return;
        }
        
        System.out.println("\nZone actuelle: " + zone.getNom() + " - " + zone.getPrixLivraison() + " FCFA");
        System.out.println("Entrez les nouvelles informations (laissez vide pour conserver)\n");
        
        Zone modifications = views.saisirZone();
        if (modifications != null) {
            modifications.setId(id);
            try {
                zoneService.modifierZone(modifications);
                System.out.println("✅ Zone modifiée avec succès !\n");
            } catch (Exception e) {
                System.out.println("❌ Erreur lors de la modification de la zone: " + e.getMessage() + "\n");
            }
        }
    }
    
    private static void supprimerZone(ZoneService zoneService, MainViews views) {
        System.out.println("\n=== Supprimer une Zone de Livraison ===");
        
        List<Zone> zones = zoneService.listerZones();
        if (zones == null || zones.isEmpty()) {
            System.out.println("📋 Aucune zone disponible à supprimer.\n");
            return;
        }
        
        views.afficherZones(zones);
        
        int id = views.demanderID("ID de la zone à supprimer");
        Zone zone = zoneService.getZoneById(id);
        
        if (zone == null) {
            System.out.println("❌ Zone introuvable.\n");
            return;
        }
        
        try {
            zoneService.supprimerZone(id);
            System.out.println("✅ Zone '" + zone.getNom() + "' supprimée avec succès !\n");
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la suppression de la zone: " + e.getMessage() + "\n");
        }
    }
}