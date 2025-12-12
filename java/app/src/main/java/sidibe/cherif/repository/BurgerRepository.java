package sidibe.cherif.repository;

import sidibe.cherif.entity.Burger;
import java.util.List;

public interface BurgerRepository {
    int insert(Burger burger);
    Burger selectById(int id);
    List<Burger> selectAll();
    boolean update(Burger burger);
    boolean delete(int id);
}
