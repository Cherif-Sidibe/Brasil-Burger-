using Microsoft.EntityFrameworkCore.Storage.ValueConversion;

namespace VueClient.Data;

/// <summary>
/// Convertisseur personnalisé pour les enums PostgreSQL
/// Génère les CAST explicites requis par PostgreSQL
/// </summary>
public class PostgresEnumValueConverter<TEnum> : ValueConverter<TEnum, string> where TEnum : struct, Enum
{
    public PostgresEnumValueConverter()
        : base(
            v => v.ToString(),  // Convertir enum C# en string
            v => Enum.Parse<TEnum>(v)) // Convertir string en enum C#
    {
    }
}
