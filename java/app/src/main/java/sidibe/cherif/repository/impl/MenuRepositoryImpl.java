package sidibe.cherif.repository.impl;

import sidibe.cherif.entity.Menu;
import sidibe.cherif.repository.MenuRepository;
import sidibe.cherif.config.DatabaseConfig;
import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class MenuRepositoryImpl implements MenuRepository {

    @Override
    public int insert(Menu menu) {
        String sql = "INSERT INTO menu (nom, prix, description, image, id_burger, id_boisson, id_frite, is_archive, created_at, updated_at) " +
                     "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, menu.getNom());
            stmt.setDouble(2, menu.getPrix());
            stmt.setString(3, menu.getDescription());
            stmt.setString(4, menu.getImage());
            stmt.setInt(5, menu.getIdBurger());
            stmt.setInt(6, menu.getIdBoisson());
            stmt.setInt(7, menu.getIdFrite());
            stmt.setBoolean(8, menu.isArchive());
            stmt.setDate(9, Date.valueOf(LocalDate.now()));
            stmt.setDate(10, Date.valueOf(LocalDate.now()));
            
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
    public Menu selectById(int id) {
        String sql = "SELECT * FROM menu WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToMenu(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<Menu> selectAll() {
        List<Menu> menus = new ArrayList<>();
        String sql = "SELECT * FROM menu ORDER BY id DESC";
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                menus.add(mapResultSetToMenu(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return menus;
    }


    @Override
    public boolean update(Menu menu) {
        String sql = "UPDATE menu SET nom = ?, prix = ?, description = ?, image = ?, id_burger = ?, id_boisson = ?, id_frite = ?, is_archive = ?, updated_at = ? WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, menu.getNom());
            stmt.setDouble(2, menu.getPrix());
            stmt.setString(3, menu.getDescription());
            stmt.setString(4, menu.getImage());
            stmt.setInt(5, menu.getIdBurger());
            stmt.setInt(6, menu.getIdBoisson());
            stmt.setInt(7, menu.getIdFrite());
            stmt.setBoolean(8, menu.isArchive());
            stmt.setDate(9, Date.valueOf(LocalDate.now()));
            stmt.setInt(10, menu.getId());
            
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public boolean delete(int id) {
        String sql = "DELETE FROM menu WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }



    private Menu mapResultSetToMenu(ResultSet rs) throws SQLException {
        Menu menu = new Menu();
        menu.setId(rs.getInt("id"));
        menu.setNom(rs.getString("nom"));
        menu.setPrix(rs.getDouble("prix"));
        menu.setDescription(rs.getString("description"));
        menu.setImage(rs.getString("image"));
        menu.setIdBurger(rs.getInt("id_burger"));
        menu.setIdBoisson(rs.getInt("id_boisson"));
        menu.setIdFrite(rs.getInt("id_frite"));
        menu.setArchive(rs.getBoolean("is_archive"));
        menu.setCreateAt(rs.getDate("created_at").toLocalDate());
        menu.setUpdateAt(rs.getDate("updated_at").toLocalDate());
        return menu;
    }
}
