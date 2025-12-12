package sidibe.cherif.repository.impl;

import sidibe.cherif.entity.Zone;
import sidibe.cherif.repository.ZoneRepository;
import sidibe.cherif.config.DatabaseConfig;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ZoneRepositoryImpl implements ZoneRepository {

    @Override
    public int insert(Zone zone) {
        String sql = "INSERT INTO zone (nom, prix, quartiers) VALUES (?, ?, ?)";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, zone.getNom());
            stmt.setDouble(2, zone.getPrixLivraison());
            stmt.setString(3, String.join(",", zone.getQuartiers()));
            
            int affectedRows = stmt.executeUpdate();
            if (affectedRows > 0) {
                try (ResultSet generatedKeys = stmt.getGeneratedKeys()) {
                    if (generatedKeys.next()) {
                        return generatedKeys.getInt(1);
                    }
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return -1;
    }

    @Override
    public Zone selectById(int id) {
        String sql = "SELECT * FROM zone WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToZone(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<Zone> selectAll() {
        List<Zone> zones = new ArrayList<>();
        String sql = "SELECT * FROM zone ORDER BY id DESC";
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                zones.add(mapResultSetToZone(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return zones;
    }

    @Override
    public boolean update(Zone zone) {
        String sql = "UPDATE zone SET nom = ?, prix = ?, quartiers = ? WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, zone.getNom());
            stmt.setDouble(2, zone.getPrixLivraison());
            stmt.setString(3, String.join(",", zone.getQuartiers()));
            stmt.setInt(4, zone.getId());
            
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public boolean delete(int id) {
        String sql = "DELETE FROM zone WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }



    private Zone mapResultSetToZone(ResultSet rs) throws SQLException {
        Zone zone = new Zone();
        zone.setId(rs.getInt("id"));
        zone.setNom(rs.getString("nom"));
        zone.setPrixLivraison(rs.getDouble("prix_livraison"));
        String quartiersStr = rs.getString("quartiers");
        zone.setQuartiers(quartiersStr != null ? List.of(quartiersStr.split(",")) : new ArrayList<>());
        return zone;
    }
}
