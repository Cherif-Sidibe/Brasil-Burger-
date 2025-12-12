package sidibe.cherif.entity;

import java.time.LocalDate;
import java.util.List;
import lombok.AllArgsConstructor;
import lombok.Getter;       
import lombok.NoArgsConstructor;
import lombok.Setter;
import lombok.ToString;

@Getter
@Setter 
@ToString
@NoArgsConstructor
@AllArgsConstructor
public class Zone {
    private int id;
    private String nom;
    private List<String> quartiers;
    private double prixLivraison;
    private boolean isArchive;
    private LocalDate createAt;
    private LocalDate updateAt;

}
