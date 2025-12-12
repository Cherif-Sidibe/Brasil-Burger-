package sidibe.cherif.service;

import sidibe.cherif.entity.Menu;
import java.util.List;

public interface MenuService {
    int creerMenu(Menu menu);
    void modifierMenu(Menu menu);
    void archiverMenu(int id);
    List<Menu> listerMenus();
    List<Menu> listerMenusActifs();
    Menu getMenuById(int id);
    double calculerPrixMenu(int id);
}
