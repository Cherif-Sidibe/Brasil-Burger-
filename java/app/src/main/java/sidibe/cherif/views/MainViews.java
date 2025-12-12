package sidibe.cherif.views;

import sidibe.cherif.entity.*;
import sidibe.cherif.service.BurgerService;
import sidibe.cherif.service.ComplementService;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.regex.Pattern;

public class MainViews {
    Scanner scan = new Scanner(System.in);
    public int afficherMenuPrincipal() {
        System.out.println("=== Menu Principal ===");
        System.out.println("1. Gérer les Burgers");
        System.out.println("2. Gérer les Compléments");
        System.out.println("3. Gérer les Menus");
        System.out.println("4. Gérer les Utilisateurs");
        System.out.println("5. Gérer les Zones de Livraison");
        System.out.println("6. Quitter");
        System.out.print("Veuillez choisir une option: ");
        int choix = scan.nextInt();
        return choix;
    }
    public int gererBurgers() {
        System.out.println("=== Gestion des Burgers ===");
        System.out.println("1. Ajouter un Burger");
        System.out.println("2. Lister un Burger");
        System.out.println("3. Modifier un Burger");
        System.out.println("4. Archiver  un Burger");
        System.out.println("5. Retour au Menu Principal");
        System.out.print("Veuillez choisir une option: "); 
        int choix = scan.nextInt();
        return choix;
    }

    public int gererComplements() {
        System.out.println("=== Gestion des Compléments ===");
        System.out.println("1. Ajouter un Complément");
        System.out.println("2. Lister un Complément");
        System.out.println("3. Modifier un Complément");
        System.out.println("4. Archiver un Complément");
        System.out.println("5. Retour au Menu Principal");
        System.out.print("Veuillez choisir une option: "); 
        int choix = scan.nextInt();
        return choix;
    }

    public int gererMenus() {
        System.out.println("=== Gestion des Menus ===");
        System.out.println("1. Ajouter un Menu");
        System.out.println("2. Lister un Menu");
        System.out.println("3. Modifier un Menu");
        System.out.println("4. Archiver un Menu");
        System.out.println("5. Retour au Menu Principal");
        System.out.print("Veuillez choisir une option: "); 
        int choix = scan.nextInt(); 
        return choix;
    }

    public int gererUtilisateurs() {
        System.out.println("=== Gestion des Utilisateurs ===");
        System.out.println("1. Ajouter un Utilisateur");
        System.out.println("2. Lister un Utilisateur");
        System.out.println("3. Modifier un Utilisateur");
        System.out.println("4. Archiver un Utilisateur");
        System.out.println("5. Retour au Menu Principal");
        System.out.print("Veuillez choisir une option: "); 
        int choix = scan.nextInt(); 
        return choix;
    }

    public int gererZonesLivraison() {
        System.out.println("=== Gestion des Zones de Livraison ===");
        System.out.println("1. Ajouter une Zone de Livraison");
        System.out.println("2. Lister une Zone de Livraison");
        System.out.println("3. Modifier une Zone de Livraison");
        System.out.println("4. Archiver une Zone de Livraison");
        System.out.println("5. Retour au Menu Principal");
        System.out.print("Veuillez choisir une option: "); 
        int choix = scan.nextInt(); 
        return choix;
    }

    public void afficherMessage(String message) {
        System.out.println(message);
    }

    // ==================== MÉTHODES DE SAISIE ====================

    
    public Burger saisirBurger() {
        scan.nextLine(); // Nettoyer le buffer
        
        String nom = saisirChaineNonVide("Nom du burger: ");
        double prix = saisirPrixValide("Prix du burger: ");
        
        System.out.print("Description du burger: ");
        String description = scan.nextLine();
        
        System.out.print("Image du burger (URL ou chemin): ");
        String image = scan.nextLine();
        
        Burger burger = new Burger();
        burger.setNom(nom);
        burger.setPrix(prix);
        burger.setDescription(description);
        burger.setImage(image);
        burger.setArchive(false);
        burger.setCreateAt(LocalDate.now());
        burger.setUpdateAt(LocalDate.now());
        
        return burger;
    }

    public Complement saisirComplement() {
        scan.nextLine(); 
        String nom = saisirChaineNonVide("Nom du complément: ");
        double prix = saisirPrixValide("Prix du complément: ");
        
        System.out.print("Image du complément (URL ou chemin): ");
        String image = scan.nextLine();
        
        TypeComplementEnum type = saisirTypeComplement();
        
        Complement complement = new Complement();
        complement.setNom(nom);
        complement.setPrix(prix);
        complement.setImage(image);
        complement.setTypeComplement(type);
        complement.setArchive(false);
        complement.setCreateAt(LocalDate.now());
        complement.setUpdateAt(LocalDate.now());
        
        return complement;
    }

    
    public Menu saisirMenu() {
        scan.nextLine(); 
        
        String nom = saisirChaineNonVide("Nom du menu: ");
        double prix = saisirPrixValide("Prix du menu: ");
        
        System.out.print("Description du menu: ");
        String description = scan.nextLine();
        
        System.out.print("Image du menu (URL ou chemin): ");
        String image = scan.nextLine();
        
        int idBurger = saisirIdPositif("ID du burger: ");
        int idBoisson = saisirIdPositif("ID de la boisson: ");
        int idFrite = saisirIdPositif("ID des frites: ");
        
        Menu menu = new Menu();
        menu.setNom(nom);
        menu.setPrix(prix);
        menu.setDescription(description);
        menu.setImage(image);
        menu.setIdBurger(idBurger);
        menu.setIdBoisson(idBoisson);
        menu.setIdFrite(idFrite);
        menu.setArchive(false);
        menu.setCreateAt(LocalDate.now());
        menu.setUpdateAt(LocalDate.now());
        
        return menu;
    }

    
    public Zone saisirZone() {
        scan.nextLine(); // Nettoyer le buffer
        
        String nom = saisirChaineNonVide("Nom de la zone: ");
        
        List<String> quartiers = new ArrayList<>();
        System.out.print("Nombre de quartiers: ");
        int nbQuartiers = scan.nextInt();
        scan.nextLine(); 
        
        for (int i = 0; i < nbQuartiers; i++) {
            String quartier = saisirChaineNonVide("Quartier " + (i + 1) + ": ");
            quartiers.add(quartier);
        }
        
        double prixLivraison = saisirPrixPositifOuZero("Prix de livraison: ");
        
        Zone zone = new Zone();
        zone.setNom(nom);
        zone.setQuartiers(quartiers);
        zone.setPrixLivraison(prixLivraison);
        zone.setArchive(false);
        zone.setCreateAt(LocalDate.now());
        zone.setUpdateAt(LocalDate.now());
        
        return zone;
    }

    
    public User saisirUser() {
        scan.nextLine(); 
        
        String nom = saisirChaineNonVide("Nom: ");
        String prenom = saisirChaineNonVide("Prénom: ");
        String email = saisirEmailValide();
        String password = saisirChaineNonVide("Mot de passe: ");
        
        System.out.print("Adresse: ");
        String adresse = scan.nextLine();
        
        String telephone = saisirTelephoneValide();
        RoleEnum role = saisirRole();
        
        User user = new User();
        user.setNom(nom);
        user.setPrenom(prenom);
        user.setEmail(email);
        user.setPassword(password);
        user.setAdresse(adresse);
        user.setTelephone(telephone);
        user.setRole(role);
        user.setArchive(false);
        user.setCreateAt(LocalDate.now());
        user.setUpdateAt(LocalDate.now());
        
        return user;
    }

    
    private String saisirChaineNonVide(String message) {
        String valeur;
        do {
            System.out.print(message);
            valeur = scan.nextLine().trim();
            if (valeur.isEmpty()) {
                System.out.println("❌ Cette valeur ne peut pas être vide. Veuillez réessayer.");
            }
        } while (valeur.isEmpty());
        return valeur;
    }

    
    private double saisirPrixValide(String message) {
        double prix;
        do {
            System.out.print(message);
            while (!scan.hasNextDouble()) {
                System.out.println("❌ Veuillez entrer un nombre valide.");
                System.out.print(message);
                scan.next();
            }
            prix = scan.nextDouble();
            scan.nextLine(); // Nettoyer le buffer
            
            if (prix <= 0) {
                System.out.println("❌ Le prix doit être supérieur à 0. Veuillez réessayer.");
            }
        } while (prix <= 0);
        return prix;
    }

    
    private double saisirPrixPositifOuZero(String message) {
        double prix;
        do {
            System.out.print(message);
            while (!scan.hasNextDouble()) {
                System.out.println("❌ Veuillez entrer un nombre valide.");
                System.out.print(message);
                scan.next();
            }
            prix = scan.nextDouble();
            scan.nextLine(); // Nettoyer le buffer
            
            if (prix < 0) {
                System.out.println("❌ Le prix ne peut pas être négatif. Veuillez réessayer.");
            }
        } while (prix < 0);
        return prix;
    }

    
    private int saisirIdPositif(String message) {
        int id;
        do {
            System.out.print(message);
            while (!scan.hasNextInt()) {
                System.out.println("❌ Veuillez entrer un nombre entier valide.");
                System.out.print(message);
                scan.next();
            }
            id = scan.nextInt();
            scan.nextLine(); 
            
            if (id <= 0) {
                System.out.println("❌ L'ID doit être supérieur à 0. Veuillez réessayer.");
            }
        } while (id <= 0);
        return id;
    }

   
    private String saisirEmailValide() {
        Pattern emailPattern = Pattern.compile("^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$");
        
        String email;
        do {
            System.out.print("Email: ");
            email = scan.nextLine().trim();
            
            if (email.isEmpty()) {
                System.out.println("❌ L'email ne peut pas être vide. Veuillez réessayer.");
            } else if (!emailPattern.matcher(email).matches()) {
                System.out.println("❌ Format d'email invalide. Format attendu: exemple@domaine.com");
            }
        } while (email.isEmpty() || !emailPattern.matcher(email).matches());
        
        return email;
    }

    
    private String saisirTelephoneValide() {
        Pattern telephonePattern = Pattern.compile("^(\\+221|00221)?[0-9]{9,10}$");
        
        String telephone;
        do {
            System.out.print("Téléphone: ");
            telephone = scan.nextLine().trim().replaceAll("\\s+", ""); 
            
            if (telephone.isEmpty()) {
                System.out.println("❌ Le téléphone ne peut pas être vide. Veuillez réessayer.");
            } else if (!telephonePattern.matcher(telephone).matches()) {
                System.out.println("❌ Format de téléphone invalide. Format attendu: +221XXXXXXXXX ou XXXXXXXXXX");
            }
        } while (telephone.isEmpty() || !telephonePattern.matcher(telephone).matches());
        
        return telephone;
    }

    
    private TypeComplementEnum saisirTypeComplement() {
        System.out.println("Type de complément:");
        System.out.println("1. BOISSON");
        System.out.println("2. FRITE");
        
        int choix;
        do {
            System.out.print("Choisissez le type (1 ou 2): ");
            while (!scan.hasNextInt()) {
                System.out.println("❌ Veuillez entrer 1 ou 2.");
                scan.next();
            }
            choix = scan.nextInt();
            scan.nextLine(); 
            
            if (choix != 1 && choix != 2) {
                System.out.println("❌ Choix invalide. Veuillez entrer 1 ou 2.");
            }
        } while (choix != 1 && choix != 2);
        
        return choix == 1 ? TypeComplementEnum.BOISSON : TypeComplementEnum.FRITES;
    }

    
    private RoleEnum saisirRole() {
        System.out.println("Rôle de l'utilisateur:");
        System.out.println("1. CLIENT");
        System.out.println("2. GESTIONNAIRE");
        System.out.println("3. LIVREUR");
        
        int choix;
        do {
            System.out.print("Choisissez le rôle (1, 2 ou 3): ");
            while (!scan.hasNextInt()) {
                System.out.println("❌ Veuillez entrer un nombre valide.");
                scan.next();
            }
            choix = scan.nextInt();
            scan.nextLine(); 
            
            if (choix < 1 || choix > 3) {
                System.out.println(" Choix invalide. Veuillez entrer 1, 2 ou 3.");
            }
        } while (choix < 1 || choix > 3);
        
        switch (choix) {
            case 1: return RoleEnum.CLIENT;
            case 2: return RoleEnum.GESTIONNAIRE;
            case 3: return RoleEnum.LIVREUR;
            default: return RoleEnum.CLIENT;
        }
    }


    public void afficherBurgers(List<Burger> burgers) {
        if (burgers == null || burgers.isEmpty()) {
            System.out.println("❌ Aucun burger trouvé.");
            return;
        }
        
        System.out.println("\n========================================");
        System.out.println("         LISTE DES BURGERS");
        System.out.println("========================================");
        
        for (Burger burger : burgers) {
            System.out.println("ID: " + burger.getId());
            System.out.println("Nom: " + burger.getNom());
            System.out.println("Prix: " + burger.getPrix() + " FCFA");
            System.out.println("Description: " + burger.getDescription());
            System.out.println("Archivé: " + (burger.isArchive() ? "Oui" : "Non"));
            System.out.println("----------------------------------------");
        }
    }

    public void afficherComplements(List<Complement> complements) {
        if (complements == null || complements.isEmpty()) {
            System.out.println("❌ Aucun complément trouvé.");
            return;
        }
        
        System.out.println("\n========================================");
        System.out.println("        LISTE DES COMPLÉMENTS");
        System.out.println("========================================");
        
        for (Complement complement : complements) {
            System.out.println("ID: " + complement.getId());
            System.out.println("Nom: " + complement.getNom());
            System.out.println("Prix: " + complement.getPrix() + " FCFA");
            System.out.println("Type: " + complement.getTypeComplement());
            System.out.println("Archivé: " + (complement.isArchive() ? "Oui" : "Non"));
            System.out.println("----------------------------------------");
        }
    }


    public void afficherMenus(List<Menu> menus, BurgerService burgerService, ComplementService complementService) {
        if (menus == null || menus.isEmpty()) {
            System.out.println("❌ Aucun menu trouvé.");
            return;
        }
        
        System.out.println("\n========================================");
        System.out.println("          LISTE DES MENUS");
        System.out.println("========================================");
        
        for (Menu menu : menus) {
            System.out.println("ID: " + menu.getId());
            System.out.println("Nom: " + menu.getNom());
            System.out.println("Prix: " + menu.getPrix() + " FCFA");
            System.out.println("Description: " + menu.getDescription());
            
            Burger burger = burgerService.getBurgerById(menu.getIdBurger());
            System.out.println("Burger: " + (burger != null ? burger.getNom() : "Inconnu"));
            
            Complement boisson = complementService.getComplementById(menu.getIdBoisson());
            System.out.println("Boisson: " + (boisson != null ? boisson.getNom() : "Inconnue"));
            
            Complement frite = complementService.getComplementById(menu.getIdFrite());
            System.out.println("Frite: " + (frite != null ? frite.getNom() : "Inconnue"));
            
            System.out.println("Archivé: " + (menu.isArchive() ? "Oui" : "Non"));
            System.out.println("----------------------------------------");
        }
    }


    public void afficherZones(List<Zone> zones) {
        if (zones == null || zones.isEmpty()) {
            System.out.println("❌ Aucune zone de livraison trouvée.");
            return;
        }
        
        System.out.println("\n========================================");
        System.out.println("     LISTE DES ZONES DE LIVRAISON");
        System.out.println("========================================");
        
        for (Zone zone : zones) {
            System.out.println("ID: " + zone.getId());
            System.out.println("Nom: " + zone.getNom());
            System.out.println("Quartiers: " + String.join(", ", zone.getQuartiers()));
            System.out.println("Prix de livraison: " + zone.getPrixLivraison() + " FCFA");
            System.out.println("Archivé: " + (zone.isArchive() ? "Oui" : "Non"));
            System.out.println("----------------------------------------");
        }
    }

    public void afficherUsers(List<User> users) {
        if (users == null || users.isEmpty()) {
            System.out.println("❌ Aucun utilisateur trouvé.");
            return;
        }
        
        System.out.println("\n========================================");
        System.out.println("       LISTE DES UTILISATEURS");
        System.out.println("========================================");
        
        for (User user : users) {
            System.out.println("ID: " + user.getId());
            System.out.println("Nom: " + user.getNom() + " " + user.getPrenom());
            System.out.println("Email: " + user.getEmail());
            System.out.println("Téléphone: " + user.getTelephone());
            System.out.println("Adresse: " + user.getAdresse());
            System.out.println("Rôle: " + user.getRole());
            System.out.println("Archivé: " + (user.isArchive() ? "Oui" : "Non"));
            System.out.println("----------------------------------------");
        }
    }

    
    public int demanderID(String entite) {
        System.out.print("Entrez l'ID " + entite + ": ");
        int id = scan.nextInt();
        scan.nextLine(); 
        return id;
    }

    
}
