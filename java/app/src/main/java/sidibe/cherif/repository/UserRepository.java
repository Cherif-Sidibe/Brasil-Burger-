package sidibe.cherif.repository;

import sidibe.cherif.entity.User;
import java.util.List;

public interface UserRepository {
    int insert(User user);
    User selectById(int id);
    List<User> selectAll();
    boolean update(User user);
    boolean delete(int id);
    User selectByEmail(String email);
    User selectByTelephone(String telephone);
    List<User> selectByRole(String role);
}
