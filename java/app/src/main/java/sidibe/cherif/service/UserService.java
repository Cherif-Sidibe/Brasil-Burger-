package sidibe.cherif.service;

import sidibe.cherif.entity.User;
import java.util.List;

public interface UserService {
    int creerUser(User user);
    void modifierUser(User user);
    void supprimerUser(int id);
    List<User> listerUsers();
    User getUserById(int id);
    User getUserByEmail(String email);
    User getUserByTelephone(String telephone);
    List<User> listerUsersParRole(String role);
}
