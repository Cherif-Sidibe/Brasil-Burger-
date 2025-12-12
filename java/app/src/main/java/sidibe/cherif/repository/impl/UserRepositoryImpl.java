package sidibe.cherif.repository.impl;

import sidibe.cherif.entity.User;
import sidibe.cherif.entity.RoleEnum;
import sidibe.cherif.repository.UserRepository;
import sidibe.cherif.config.DatabaseConfig;
import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class UserRepositoryImpl implements UserRepository {

    @Override
    public int insert(User user) {
        String sql = "INSERT INTO \"user\" (nom, prenom, email, password, adresse, telephone, role, is_archive, created_at, updated_at) " +
                     "VALUES (?, ?, ?, ?, ?, ?, ?::role_enum, ?, ?, ?)";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, user.getNom());
            stmt.setString(2, user.getPrenom());
            stmt.setString(3, user.getEmail());
            stmt.setString(4, user.getPassword());
            stmt.setString(5, user.getAdresse());
            stmt.setString(6, user.getTelephone());
            stmt.setString(7, user.getRole().toString());
            stmt.setBoolean(8, user.isArchive());
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
    public User selectById(int id) {
        String sql = "SELECT * FROM \"user\" WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToUser(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<User> selectAll() {
        List<User> users = new ArrayList<>();
        String sql = "SELECT * FROM \"user\" ORDER BY id DESC";
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                users.add(mapResultSetToUser(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return users;
    }

    @Override
    public boolean update(User user) {
        String sql = "UPDATE \"user\" SET nom = ?, prenom = ?, email = ?, password = ?, adresse = ?, telephone = ?, role = ?::role_enum, is_archive = ?, updated_at = ? WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, user.getNom());
            stmt.setString(2, user.getPrenom());
            stmt.setString(3, user.getEmail());
            stmt.setString(4, user.getPassword());
            stmt.setString(5, user.getAdresse());
            stmt.setString(6, user.getTelephone());
            stmt.setString(7, user.getRole().toString());
            stmt.setBoolean(8, user.isArchive());
            stmt.setDate(9, Date.valueOf(LocalDate.now()));
            stmt.setInt(10, user.getId());
            
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public boolean delete(int id) {
        String sql = "DELETE FROM \"user\" WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public User selectByEmail(String email) {
        String sql = "SELECT * FROM \"user\" WHERE email = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, email);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToUser(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public User selectByTelephone(String telephone) {
        String sql = "SELECT * FROM \"user\" WHERE telephone = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, telephone);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToUser(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<User> selectByRole(String role) {
        List<User> users = new ArrayList<>();
        String sql = "SELECT * FROM \"user\" WHERE role = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, role);
            try (ResultSet rs = stmt.executeQuery()) {
                while (rs.next()) {
                    users.add(mapResultSetToUser(rs));
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return users;
    }

    private User mapResultSetToUser(ResultSet rs) throws SQLException {
        User user = new User();
        user.setId(rs.getInt("id"));
        user.setNom(rs.getString("nom"));
        user.setPrenom(rs.getString("prenom"));
        user.setEmail(rs.getString("email"));
        user.setPassword(rs.getString("password"));
        user.setAdresse(rs.getString("adresse"));
        user.setTelephone(rs.getString("telephone"));
        user.setRole(RoleEnum.valueOf(rs.getString("role")));
        user.setArchive(rs.getBoolean("is_archive"));
        user.setCreateAt(rs.getDate("created_at").toLocalDate());
        user.setUpdateAt(rs.getDate("updated_at").toLocalDate());
        return user;
    }
}
