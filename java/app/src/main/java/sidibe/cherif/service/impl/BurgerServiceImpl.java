package sidibe.cherif.service.impl;

import sidibe.cherif.entity.Burger;
import sidibe.cherif.repository.BurgerRepository;
import sidibe.cherif.service.BurgerService;

import java.util.List;

public class BurgerServiceImpl implements BurgerService {
    private final BurgerRepository burgerRepository;

    public BurgerServiceImpl(BurgerRepository burgerRepository) {
        if (burgerRepository == null) {
            throw new IllegalArgumentException("BurgerRepository ne peut pas être null");
        }
        this.burgerRepository = burgerRepository;
    }

    @Override
    public int creerBurger(Burger burger) {
        return burgerRepository.insert(burger);
    }

    @Override
    public void modifierBurger(Burger burger) {
        burgerRepository.update(burger);
    }

    @Override
    public void archiverBurger(int id) {
        Burger burger = burgerRepository.selectById(id);
        if (burger != null) {
            burger.setArchive(true);
            burgerRepository.update(burger);
        }
    }

    @Override
    public List<Burger> listerBurgers() {
        return burgerRepository.selectAll();
    }

    @Override
    public List<Burger> listerBurgersActifs() {
        return burgerRepository.selectAll().stream()
                .filter(burger -> !burger.isArchive())
                .collect(java.util.stream.Collectors.toList());
    }

    @Override
    public Burger getBurgerById(int id) {
        return burgerRepository.selectById(id);
    }
}
