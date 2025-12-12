package sidibe.cherif.service;

import sidibe.cherif.entity.Burger;
import java.util.List;

public interface BurgerService {
    int creerBurger(Burger burger);
    void modifierBurger(Burger burger);
    void archiverBurger(int id);
    List<Burger> listerBurgers();
    List<Burger> listerBurgersActifs();
    Burger getBurgerById(int id);
}
