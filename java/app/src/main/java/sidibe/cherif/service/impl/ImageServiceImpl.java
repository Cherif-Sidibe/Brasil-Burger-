package sidibe.cherif.service.impl;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;
import sidibe.cherif.config.CloudinaryConfig;
import sidibe.cherif.service.ImageService;

import java.io.File;
import java.util.Map;

public class ImageServiceImpl implements ImageService {
    
    private final Cloudinary cloudinary;
    
    public ImageServiceImpl() {
        this.cloudinary = CloudinaryConfig.getInstance();
    }
    
    @Override
    public String uploadImage(String cheminFichier, String dossier) {
        try {
            File fichier = new File(cheminFichier);
            
            if (!fichier.exists()) {
                System.out.println("❌ Fichier introuvable: " + cheminFichier);
                return null;
            }
            
            Map<String, Object> uploadResult = cloudinary.uploader().upload(fichier, 
                ObjectUtils.asMap(
                    "folder", "brasil_burger/" + dossier,
                    "use_filename", true,
                    "unique_filename", true
                )
            );
            
            String url = (String) uploadResult.get("secure_url");
            System.out.println("✅ Image uploadée avec succès: " + url);
            return url;
            
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'upload: " + e.getMessage());
            return null;
        }
    }
    
    @Override
    public boolean deleteImage(String imageUrl) {
        if (imageUrl == null || !imageUrl.contains("cloudinary.com")) {
            return false;
        }
        
        try {
            String publicId = extractPublicId(imageUrl);
            if (publicId == null) {
                return false;
            }
            
            Map<String, Object> result = cloudinary.uploader().destroy(publicId, ObjectUtils.emptyMap());
            return "ok".equals(result.get("result"));
            
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la suppression: " + e.getMessage());
            return false;
        }
    }
    
    @Override
    public boolean fileExists(String cheminFichier) {
        if (cheminFichier == null || cheminFichier.trim().isEmpty()) {
            return false;
        }
        File fichier = new File(cheminFichier);
        return fichier.exists() && fichier.isFile();
    }
    
    /**
     * Extrait le public_id d'une URL Cloudinary
     * @param url URL Cloudinary complète
     * @return public_id ou null si impossible
     */
    private String extractPublicId(String url) {
        try {
            String[] parts = url.split("/upload/");
            if (parts.length < 2) return null;
            
            String pathAfterUpload = parts[1];
            int versionIndex = pathAfterUpload.indexOf('/');
            if (versionIndex > 0) {
                pathAfterUpload = pathAfterUpload.substring(versionIndex + 1);
            }
            
            int extensionIndex = pathAfterUpload.lastIndexOf('.');
            if (extensionIndex > 0) {
                return pathAfterUpload.substring(0, extensionIndex);
            }
            
            return pathAfterUpload;
        } catch (Exception e) {
            return null;
        }
    }
}
