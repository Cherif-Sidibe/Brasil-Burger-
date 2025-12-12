package sidibe.cherif.service.impl;

import sidibe.cherif.entity.User;
import sidibe.cherif.repository.UserRepository;
import sidibe.cherif.service.UserService;

import java.util.List;

public class UserServiceImpl implements UserService {
    private final UserRepository userRepository;

    public UserServiceImpl(UserRepository userRepository) {
        if (userRepository == null) {
            throw new IllegalArgumentException("UserRepository ne peut pas être null");
        }
        this.userRepository = userRepository;
    }

    @Override
    public int creerUser(User user) {
        return userRepository.insert(user);
    }

    @Override
    public void modifierUser(User user) {
        userRepository.update(user);
    }

    @Override
    public void supprimerUser(int id) {
        userRepository.delete(id);
    }

    @Override
    public List<User> listerUsers() {
        return userRepository.selectAll();
    }

    @Override
    public User getUserById(int id) {
        return userRepository.selectById(id);
    }

    @Override
    public User getUserByEmail(String email) {
        return userRepository.selectByEmail(email);
    }

    @Override
    public User getUserByTelephone(String telephone) {
        return userRepository.selectByTelephone(telephone);
    }

    @Override
    public List<User> listerUsersParRole(String role) {
        return userRepository.selectByRole(role);
    }
}
