package sidibe.cherif.service.impl;

import sidibe.cherif.entity.Zone;
import sidibe.cherif.repository.ZoneRepository;
import sidibe.cherif.service.ZoneService;

import java.util.List;

public class ZoneServiceImpl implements ZoneService {
    private final ZoneRepository zoneRepository;

    public ZoneServiceImpl(ZoneRepository zoneRepository) {
        if (zoneRepository == null) {
            throw new IllegalArgumentException("ZoneRepository ne peut pas être null");
        }
        this.zoneRepository = zoneRepository;
    }

    @Override
    public int creerZone(Zone zone) {
        return zoneRepository.insert(zone);
    }

    @Override
    public void modifierZone(Zone zone) {
        zoneRepository.update(zone);
    }

    @Override
    public void supprimerZone(int id) {
        zoneRepository.delete(id);
    }

    @Override
    public List<Zone> listerZones() {
        return zoneRepository.selectAll();
    }

    @Override
    public Zone getZoneById(int id) {
        return zoneRepository.selectById(id);
    }
}
