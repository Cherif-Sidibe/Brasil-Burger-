package sidibe.cherif.repository;

import sidibe.cherif.entity.Menu;
import java.util.List;

public interface MenuRepository {
    int insert(Menu menu);
    Menu selectById(int id);
    List<Menu> selectAll();
    boolean update(Menu menu);
    boolean delete(int id);
}
