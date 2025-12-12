package sidibe.cherif.repository.impl;

import sidibe.cherif.entity.Complement;
import sidibe.cherif.entity.TypeComplementEnum;
import sidibe.cherif.repository.ComplementRepository;
import sidibe.cherif.config.DatabaseConfig;
import java.sql.*;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

public class ComplementRepositoryImpl implements ComplementRepository {

    @Override
    public int insert(Complement complement) {
        String sql = "INSERT INTO complement (nom, prix, image, type_complement, is_archive, created_at, updated_at) " +
                     "VALUES (?, ?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            
            stmt.setString(1, complement.getNom());
            stmt.setDouble(2, complement.getPrix());
            stmt.setString(3, complement.getImage());
            stmt.setString(4, complement.getTypeComplement().toString());
            stmt.setBoolean(5, complement.isArchive());
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
    public Complement selectById(int id) {
        String sql = "SELECT * FROM complement WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            try (ResultSet rs = stmt.executeQuery()) {
                if (rs.next()) {
                    return mapResultSetToComplement(rs);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public List<Complement> selectAll() {
        List<Complement> complements = new ArrayList<>();
        String sql = "SELECT * FROM complement ORDER BY id DESC";
        try (Connection conn = DatabaseConfig.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            
            while (rs.next()) {
                complements.add(mapResultSetToComplement(rs));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return complements;
    }


    @Override
    public boolean update(Complement complement) {
        String sql = "UPDATE complement SET nom = ?, prix = ?, image = ?, type_complement = ?, is_archive = ?, updated_at = ? WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setString(1, complement.getNom());
            stmt.setDouble(2, complement.getPrix());
            stmt.setString(3, complement.getImage());
            stmt.setString(4, complement.getTypeComplement().toString());
            stmt.setBoolean(5, complement.isArchive());
            stmt.setDate(6, Date.valueOf(LocalDate.now()));
            stmt.setInt(7, complement.getId());
            
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }

    @Override
    public boolean delete(int id) {
        String sql = "DELETE FROM complement WHERE id = ?";
        try (Connection conn = DatabaseConfig.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            
            stmt.setInt(1, id);
            return stmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return false;
    }



    private Complement mapResultSetToComplement(ResultSet rs) throws SQLException {
        Complement complement = new Complement();
        complement.setId(rs.getInt("id"));
        complement.setNom(rs.getString("nom"));
        complement.setPrix(rs.getDouble("prix"));
        complement.setImage(rs.getString("image"));
        complement.setTypeComplement(TypeComplementEnum.valueOf(rs.getString("type_complement")));        
        complement.setArchive(rs.getBoolean("is_archive"));
        complement.setCreateAt(rs.getDate("created_at").toLocalDate());
        complement.setUpdateAt(rs.getDate("updated_at").toLocalDate());
        return complement;
    }
}
