package sidibe.cherif.views;

import java.util.Scanner;

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

    
}
