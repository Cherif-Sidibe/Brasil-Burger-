package sidibe.cherif.repository;

import sidibe.cherif.entity.Zone;
import java.util.List;

public interface ZoneRepository {
    int insert(Zone zone);
    Zone selectById(int id);
    List<Zone> selectAll();
    boolean update(Zone zone);
    boolean delete(int id);
}
