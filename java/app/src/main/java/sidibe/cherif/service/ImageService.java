package sidibe.cherif.service;

public interface ImageService {
    /**
     * Upload une image vers Cloudinary
     * @param cheminFichier Chemin absolu du fichier image
     * @param dossier Dossier dans Cloudinary (ex: "burgers", "complements")
     * @return URL de l'image uploadée ou null si erreur
     */
    String uploadImage(String cheminFichier, String dossier);
    
    /**
     * Supprime une image de Cloudinary
     * @param imageUrl URL complète de l'image Cloudinary
     * @return true si suppression réussie
     */
    boolean deleteImage(String imageUrl);
    
    /**
     * Vérifie si un fichier existe
     * @param cheminFichier Chemin du fichier
     * @return true si le fichier existe
     */
    boolean fileExists(String cheminFichier);
}
