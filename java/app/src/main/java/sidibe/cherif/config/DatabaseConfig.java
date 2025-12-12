package sidibe.cherif.config;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConfig {
    private static final String URL = "jdbc:postgresql://ep-quiet-silence-ae71zpqr-pooler.c-2.us-east-2.aws.neon.tech/Brasil_Burger?sslmode=require";
    private static final String USER = "neondb_owner";
    private static final String PASSWORD = "npg_PUa4oqjNxwT9";

    static {
        try {
            Class.forName("org.postgresql.Driver");
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    public static Connection getConnection() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }

    public static void closeConnection(Connection connection) {
        if (connection != null) {
            try {
                connection.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }
}
