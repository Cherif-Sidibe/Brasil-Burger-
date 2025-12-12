package sidibe.cherif.service;

import sidibe.cherif.entity.Zone;
import java.util.List;

public interface ZoneService {
    int creerZone(Zone zone);
    void modifierZone(Zone zone);
    void supprimerZone(int id);
    List<Zone> listerZones();
    Zone getZoneById(int id);
}
