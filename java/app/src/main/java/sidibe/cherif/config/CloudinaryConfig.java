package sidibe.cherif.config;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;

public class CloudinaryConfig {
    private static Cloudinary cloudinary;
    
    private CloudinaryConfig() {}
    
    public static Cloudinary getInstance() {
        if (cloudinary == null) {
            cloudinary = new Cloudinary(ObjectUtils.asMap(
                "cloud_name", "dsjtyzezc",
                "api_key", "594194552665413",
                "api_secret", "2NN1cWOzLljt58QoWZHAm7ANBW8"
            ));
        }
        return cloudinary;
    }
}
