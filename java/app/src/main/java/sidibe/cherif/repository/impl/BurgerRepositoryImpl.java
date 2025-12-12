package sidibe.cherif.repository.impl;

import sidibe.cherif.entity.Burger;
import sidibe.cherif.repository.BurgerRepository;
import sidibe.cherif.config.DatabaseConfig;
import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class BurgerRepositoryImpl implements BurgerRepository {

    @Override
    public int insert(Burger burger) {
        String sql = "INSERT INTO burger (nom, prix, description, image, is_archive, created_at, updated_at) " +
                     "VALUES (?, ?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setDouble(2, burger.getPrix());
            stmt.setString(3, burger.getDescription());
            stmt.setString(4, burger.getImage());
            stmt.setBoolean(5, burger.isArchive());
            stmt.setDate(6, Date.valueOf(LocalDate.now()));
            stmt.setDate(7, Date.valueOf(LocalDate.now()));
            
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
    public Burger selectById(int id) {
        String sql = "SELECT * FROM burger WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToBurger(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<Burger> selectAll() {
        List<Burger> burgers = new ArrayList<>();
        String sql = "SELECT * FROM burger ORDER BY id DESC";
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                burgers.add(mapResultSetToBurger(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return burgers;
    }


    @Override
    public boolean update(Burger burger) {
        String sql = "UPDATE burger SET nom = ?, prix = ?, description = ?, image = ?, is_archive = ?, updated_at = ? WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, burger.getNom());
            stmt.setDouble(2, burger.getPrix());
            stmt.setString(3, burger.getDescription());
            stmt.setString(4, burger.getImage());
            stmt.setBoolean(5, burger.isArchive());
            stmt.setDate(6, Date.valueOf(LocalDate.now()));
            stmt.setInt(7, burger.getId());
            
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public boolean delete(int id) {
        String sql = "DELETE FROM burger WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

   

    private Burger mapResultSetToBurger(ResultSet rs) throws SQLException {
        Burger burger = new Burger();
        burger.setId(rs.getInt("id"));
        burger.setNom(rs.getString("nom"));
        burger.setPrix(rs.getDouble("prix"));
        burger.setDescription(rs.getString("description"));
        burger.setImage(rs.getString("image"));
        burger.setArchive(rs.getBoolean("is_archive"));
        burger.setCreateAt(rs.getDate("created_at").toLocalDate());
        burger.setUpdateAt(rs.getDate("updated_at").toLocalDate());
        return burger;
    }
}
