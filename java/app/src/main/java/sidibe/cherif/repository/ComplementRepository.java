package sidibe.cherif.repository;

import sidibe.cherif.entity.Complement;
import java.util.List;

public interface ComplementRepository {
    int insert(Complement complement);
    Complement selectById(int id);
    List<Complement> selectAll();
    boolean update(Complement complement);
    boolean delete(int id);
}
