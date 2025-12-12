package sidibe.cherif;

import sidibe.cherif.views.MainViews;

public class Main {
    public static void main(String[] args) {
        MainViews views = new MainViews();
        
        System.out.println("╔══════════════════════════════════════╗");
        System.out.println("║   BIENVENUE CHEZ BRASIL BURGER       ║");
        System.out.println("╚══════════════════════════════════════╝\n");
        
        boolean continuer = true;
        
        while (continuer) {
            int choix = views.afficherMenuPrincipal();
            
            switch (choix) {
                case 1:
                    System.out.println("Gestion des Burgers sélectionnée");
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
                    System.out.println("\n👋 Merci d'avoir utilisé Brasil Burger. À bientôt !");
                    continuer = false;
                    break;
                default:
                    System.out.println("❌ Option invalide. Veuillez réessayer.\n");
            }
        }
    }
}