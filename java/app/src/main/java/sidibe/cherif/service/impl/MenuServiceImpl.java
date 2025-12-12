package sidibe.cherif.service.impl;

import sidibe.cherif.entity.Menu;
import sidibe.cherif.repository.MenuRepository;
import sidibe.cherif.service.MenuService;

import java.util.List;

public class MenuServiceImpl implements MenuService {
    private final MenuRepository menuRepository;

    public MenuServiceImpl(MenuRepository menuRepository) {
        if (menuRepository == null) {
            throw new IllegalArgumentException("MenuRepository ne peut pas être null");
        }
        this.menuRepository = menuRepository;
    }

    @Override
    public int creerMenu(Menu menu) {
        return menuRepository.insert(menu);
    }

    @Override
    public void modifierMenu(Menu menu) {
        menuRepository.update(menu);
    }

    @Override
    public void archiverMenu(int id) {
        Menu menu = menuRepository.selectById(id);
        if (menu != null) {
            menu.setArchive(true);
            menuRepository.update(menu);
        }
    }

    @Override
    public List<Menu> listerMenus() {
        return menuRepository.selectAll();
    }

    @Override
    public List<Menu> listerMenusActifs() {
        return menuRepository.selectAll().stream()
                .filter(menu -> !menu.isArchive())
                .collect(java.util.stream.Collectors.toList());
    }

    @Override
    public Menu getMenuById(int id) {
        return menuRepository.selectById(id);
    }
}
